<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Requerimiento;
use Illuminate\Support\Facades\DB;  

class RequerimientoController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $requerimientos = Requerimiento::join('ventas','requerimientos.idventa','=','ventas.id')
            // ->join('clientes', 'ventas.idcliente', '=', 'clientes.id')
            ->join('personas', 'ventas.idcliente', '=', 'personas.id')
            ->select('requerimientos.id','ventas.fecha_hora','personas.nombre as nombre_cliente','requerimientos.descripcion', 'requerimientos.requerimiento', 'requerimientos.tiempo_inst_softw')
            ->orderBy('requerimientos.created_at', 'desc')->paginate(3);
        }
        else {
            $requerimientos = Requerimiento::join('ventas','requerimientos.idventa','=','ventas.id')
            // ->join('clientes', 'ventas.idcliente', '=', 'clientes.id')
            ->join('personas', 'ventas.idcliente', '=', 'personas.id')
            ->select('requerimientos.id','personas.nombre as nombre_cliente','ventas.fecha_hora','requerimientos.descripcion', 'requerimientos.requerimiento','requerimientos.tiempo_inst_softw')
            ->where('requerimientos.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('requerimientos.created_at', 'desc')->paginate(3);
        }
        

        return [
            'pagination' => [
                'total'        => $requerimientos->total(),
                'current_page' => $requerimientos->currentPage(),
                'per_page'     => $requerimientos->perPage(),
                'last_page'    => $requerimientos->lastPage(),
                'from'         => $requerimientos->firstItem(),
                'to'           => $requerimientos->lastItem(),
            ],
            'requerimientos' => $requerimientos
        ];  
    }
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');
        $requerimiento = Requerimiento::findOrFail($request->requerimiento_id);
        $requerimiento->tiempo_inst_softw = $request->tiempo_inst_softw;
        $requerimiento->save();
    }
}

