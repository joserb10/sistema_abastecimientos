<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Observacion;

class ObservacionController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $observations = Observation::join('detalle_ventas','observations.idventa','=','detalle_ventas.idventa')
            ->join('articulos','detalle_ventas.idarticulo','=','articulos.id')
            ->join('ventas','observations.idventa','=','ventas.id')
            ->select('detalle_ventas.cantidad','detalle_ventas.precio','detalle_ventas.descuento',
            'articulos.nombre as articulo')
            ->where('ventas.idcliente','=',\Auth::user()->id)
            ->orderBy('detalle_ventas.id', 'desc')->paginate(3);
        }
        else{
            $observations = Observation::join('ventas','observations.idventa','=','ventas.id')
            ->join('clientes','ventas.idcliente','=','ventas.id')
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

    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        try{
            DB::beginTransaction();

            $observacion = new Observacion();
            $observacion->detalle_venta_id = $request->detalle_venta_id;
            $observacion->descripcion = $request->descripcion;
            $observacion->estado = 'Pendiente';
            $observacion->save();

            DB::commit();
           
        } catch (Exception $e){
            DB::rollBack();
        }
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $observacion = Observacion::findOrFail($request->id);
        $observacion->estado = 'Resuelto';
        $observacion->save();
    }
}
