<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  
use Carbon\Carbon;
use App\Venta;
use App\Ingreso;
use App\Articulo;
use App\DetalleVenta;
use App\User;
use App\Seguimiento;
use App\Requerimiento;
use App\Notifications\NotifyAdmin;

class VentaController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
            ->join('users','ventas.idusuario','=','users.id')
            ->select('ventas.id','ventas.tipo_comprobante','ventas.serie_comprobante',
            'ventas.num_comprobante','ventas.fecha_hora','ventas.impuesto','ventas.total',
            'ventas.estado','personas.nombre','users.usuario')
            ->orderBy('ventas.id', 'desc')->paginate(3);
        }
        else{
            $ventas = Venta::join('personas','ventas.idcliente','=','personas.id')
            ->join('users','ventas.idusuario','=','users.id')
            ->select('ventas.id','ventas.tipo_comprobante','ventas.serie_comprobante',
            'ventas.num_comprobante','ventas.fecha_hora','ventas.impuesto','ventas.total',
            'ventas.estado','personas.nombre','users.usuario')
            ->where('ventas.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('ventas.id', 'desc')->paginate(3);
        }
        
        return [
            'pagination' => [
                'total'        => $ventas->total(),
                'current_page' => $ventas->currentPage(),
                'per_page'     => $ventas->perPage(),
                'last_page'    => $ventas->lastPage(),
                'from'         => $ventas->firstItem(),
                'to'           => $ventas->lastItem(),
            ],
            'ventas' => $ventas
        ];
    }
    public function obtenerCabecera(Request $request){
        if (!$request->ajax()) return redirect('/');

        $id = $request->id;
        $venta = Venta::join('personas','ventas.idcliente','=','personas.id')
        ->join('users','ventas.idusuario','=','users.id')
        ->select('ventas.id','ventas.tipo_comprobante','ventas.serie_comprobante',
        'ventas.num_comprobante','ventas.fecha_hora','ventas.impuesto','ventas.total',
        'ventas.estado','personas.nombre','users.usuario')
        ->where('ventas.id','=',$id)
        ->orderBy('ventas.id', 'desc')->take(1)->get();
        
        return ['venta' => $venta];
    }
    public function obtenerDetalles(Request $request){
        if (!$request->ajax()) return redirect('/');

        $id = $request->id;
        $detalles = DetalleVenta::join('articulos','detalle_ventas.idarticulo','=','articulos.id')
        ->select('detalle_ventas.cantidad','detalle_ventas.precio','detalle_ventas.descuento',
        'articulos.nombre as articulo')
        ->where('detalle_ventas.idventa','=',$id)
        ->orderBy('detalle_ventas.id', 'desc')->get();
        
        return ['detalles' => $detalles];
    }
    public function pdf(Request $request,$id){
        $venta = Venta::join('personas','ventas.idcliente','=','personas.id')
        ->join('users','ventas.idusuario','=','users.id')
        ->select('ventas.id','ventas.tipo_comprobante','ventas.serie_comprobante',
        'ventas.num_comprobante','ventas.created_at','ventas.impuesto','ventas.total',
        'ventas.estado','personas.nombre','personas.tipo_documento','personas.num_documento',
        'personas.direccion','personas.email',
        'personas.telefono','users.usuario')
        ->where('ventas.id','=',$id)
        ->orderBy('ventas.id', 'desc')->take(1)->get();

        $detalles = DetalleVenta::join('articulos','detalle_ventas.idarticulo','=','articulos.id')
        ->select('detalle_ventas.cantidad','detalle_ventas.precio','detalle_ventas.descuento',
        'articulos.nombre as articulo')
        ->where('detalle_ventas.idventa','=',$id)
        ->orderBy('detalle_ventas.id', 'desc')->get();

        $numventa=Venta::select('num_comprobante')->where('id',$id)->get();

        $pdf = \PDF::loadView('pdf.venta',['venta'=>$venta,'detalles'=>$detalles]);
        return $pdf->download('venta-'.$numventa[0]->num_comprobante.'.pdf');
    }

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        try{
            DB::beginTransaction();

            $mytime= Carbon::now('America/Lima');

            $venta = new Venta();
            $venta->idcliente = $request->idcliente;
            $venta->idusuario = \Auth::user()->id;
            $venta->tipo_comprobante = $request->tipo_comprobante;
            $venta->serie_comprobante = $request->serie_comprobante;
            $venta->num_comprobante = $request->num_comprobante;
            $venta->fecha_hora = $mytime->toDateTimeString();
            $venta->impuesto = $request->impuesto;
            $venta->total = $request->total;
            $venta->estado = 'Registrado';
            $venta->save();          

            // Registro requerimiento si existiese
            if($request->requerimiento and $request->descripcion_req){
                $requerimiento = new Requerimiento();
                $requerimiento->idventa = $venta->id;
                $requerimiento->requerimiento = $request->requerimiento;
                $requerimiento->descripcion = $request->descripcion_req;
                $requerimiento->tiempo_inst_softw = 1;
                $requerimiento->save();
            }
                
            $detalles = $request->data;//Array de detalles
            //Recorro todos los elementos
            $cantidad_articulos = array();
            $id_articulos = array();
            $detalle_ventas = array();

            foreach($detalles as $ep=>$det)
            {
                $detalle = new DetalleVenta();
                $detalle->idventa = $venta->id;
                $detalle->idarticulo = $det['idarticulo'];
                $detalle->cantidad = $det['cantidad'];
                $detalle->precio = $det['precio'];
                $detalle->descuento = $det['descuento'];         
                $detalle->save();

                array_push($cantidad_articulos, $detalle->cantidad);    
                array_push($id_articulos, $detalle->idarticulo);  

                array_push($detalle_ventas, $detalle);

            }  
            
            for ($i = 0; $i < sizeof($id_articulos); $i++) { 
                $articulo = Articulo::findOrFail($id_articulos[$i]);
                $articulo->stock -= $cantidad_articulos[$i];
                $articulo->save();  

                //CReación de seguimiento
                $seguimiento = new Seguimiento();
                $seguimiento->id_detalle_venta = $detalle_ventas[$i]['id'];

                if($articulo->stock<0){
                    $seguimiento->fecha_proceso_proveedor = strtotime($articulo->tiempo_entrega_proveedor . ' day');
                    $seguimiento->fecha_proceso_proveedor = date('d-m-Y', $seguimiento->fecha_proceso_proveedor);
                } else {
                    $seguimiento->fecha_proceso_proveedor = null;
                }

                if($seguimiento->fecha_proceso_proveedor){
                    $seguimiento->fecha_proceso_almacen = strtotime('1 day', strtotime($seguimiento->fecha_proceso_proveedor)); 
                    $seguimiento->fecha_proceso_almacen = date('d-m-Y', $seguimiento->fecha_proceso_almacen);
                } else{
                    $seguimiento->fecha_proceso_almacen = strtotime('1 day');
                    $seguimiento->fecha_proceso_almacen = date('d-m-Y', $seguimiento->fecha_proceso_almacen); 
                }

                $requerimiento = Requerimiento::where('idventa', '=',$detalle_ventas[$i]['idventa'])->get();
                if(isset($requerimiento[0])){
                    if($articulo->idcategoria==2 or $articulo->idcategoria==6 or $articulo->idcategoria==9)
                    $seguimiento->fecha_proceso_inst_softw = strtotime($requerimiento[0]['tiempo_inst_softw'] . ' day', strtotime($seguimiento->fecha_proceso_almacen));
                    $seguimiento->fecha_proceso_inst_softw = date('d-m-Y', $seguimiento->fecha_proceso_inst_softw);
                } else {
                    $seguimiento->fecha_proceso_inst_softw = null;
                }

                if($seguimiento->fecha_proceso_inst_softw!=null){
                    $seguimiento->fecha_entrega = $seguimiento->fecha_proceso_inst_softw;
                    // $seguimiento->fecha_entrega = date('d.m.Y', $seguimiento->fecha_entrega);
                } else {
                    $seguimiento->fecha_entrega = $seguimiento->fecha_proceso_almacen;
                    // $seguimiento->fecha_entrega = date('d.m.Y', $seguimiento->fecha_entrega);
                }

                $seguimiento->fecha_entrega_real = null;
                $seguimiento->estado_entrega = 'En proceso';
                $seguimiento->save();
                //Fin creación seguimiento

            }
             
            $fechaActual= date('Y-m-d');
            $numVentas = DB::table('ventas')->whereDate('created_at', $fechaActual)->count(); 
            $numIngresos = DB::table('ingresos')->whereDate('created_at',$fechaActual)->count(); 

            $arregloDatos = [ 
            'ventas' => [ 
                        'numero' => $numVentas, 
                        'msj' => 'Ventas' 
                    ], 
            'ingresos' => [ 
                        'numero' => $numIngresos, 
                        'msj' => 'Ingresos' 
                    ] 
            ];                
            $allUsers = User::all();

            foreach ($allUsers as $notificar) { 
                User::findOrFail($notificar->id)->notify(new NotifyAdmin($arregloDatos)); 
            }

            DB::commit();
            return [
                'id' => $venta->id
            ];
        } catch (Exception $e){
            DB::rollBack();
        }
    }

    public function desactivar(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $venta = Venta::findOrFail($request->id);
        $venta->estado = 'Anulado';
        $venta->save();
    }
}
