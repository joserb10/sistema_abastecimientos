<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Seguimiento;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;



class SeguimientoController extends Controller
{
    public function index(Request $request)
    {
        // if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $seguimientos = Seguimiento::join('detalle_ventas','seguimientos.id_detalle_venta','=','detalle_ventas.id')
            ->join('ventas','detalle_ventas.idventa','=','ventas.id')
            // ->join('clientes','ventas.idcliente','=','clientes.id')
            ->join('personas','ventas.idcliente','=','personas.id')
            ->select('seguimientos.id', 'detalle_ventas.id as detalle_venta_id', 'seguimientos.fecha_proceso_proveedor',
            'seguimientos.fecha_proceso_inst_softw', 'seguimientos.fecha_proceso_almacen', 'personas.nombre as nombre_cliente',
            'seguimientos.fecha_entrega', 'seguimientos.fecha_entrega_real', 'seguimientos.estado_entrega')
            ->orderBy('seguimientos.created_at', 'desc')->paginate(3);
        }
        // elseif ($request=='created_at' or $request=='cantidad_solicitada'){
        else {    
            $seguimientos = Seguimiento::join('detalle_ventas','seguimientos.id_detalle_venta','=','detalle_ventas.id')
            ->select('seguimientos.id', 'detalle_ventas.id as detalle_venta_id', 'seguimientos.fecha_proceso_proveedor',
            'seguimientos.fecha_proceso_inst_softw', 'seguimientos.fecha_proceso_almacen',
            'seguimientos.fecha_entrega', 'seguimientos.fecha_entrega_real', 'seguimientos.estado_entrega')
            ->where('seguimientos.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('seguimientos.created_at', 'desc')->paginate(3);
        }
        // else {
        //     $seguimientos = Seguimiento::join('articulos','solicitudcompras.idarticulo','=','articulos.id')
        //     ->select('articulos.id as idarticulo','articulos.codigo as codigoarticulo','articulos.nombre','solicitudcompras.id', 'solicitudcompras.cantidad_solicitada', 'solicitudcompras.estado')
        //     ->where('articulos.'.$criterio, 'like', '%'. $buscar . '%')
        //     ->orderBy('solicitudcompras.created_at', 'desc')->paginate(3);
        // }
        

        return [
            'pagination' => [
                'total'        => $seguimientos->total(),
                'current_page' => $seguimientos->currentPage(),
                'per_page'     => $seguimientos->perPage(),
                'last_page'    => $seguimientos->lastPage(),
                'from'         => $seguimientos->firstItem(),
                'to'           => $seguimientos->lastItem(),
            ],
            'seguimientos' => $seguimientos
        ];
    }

    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $seguimiento = Seguimiento::findOrFail($request->id);
        $seguimiento->estado_entrega = 'Entregado';
        $seguimiento->fecha_entrega_real = Carbon::now()->format('d-m-Y');
        // $seguimiento->fecha_entrega_real = date('d.m.Y, g a', $seguimiento->fecha_entrega_real);
        $seguimiento->save();
    }
}
