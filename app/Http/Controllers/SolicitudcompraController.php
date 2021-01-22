<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Solicitudcompra;
use App\Compra;
use Illuminate\Support\Facades\DB;


class SolicitudcompraController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        $buscar = $request->buscar;
        $criterio = $request->criterio;
        
        if ($buscar==''){
            $solicitudes = Solicitudcompra::join('articulos','solicitudcompras.idarticulo','=','articulos.id')
            ->select('articulos.id as idarticulo','articulos.codigo as codigoarticulo','articulos.nombre','solicitudcompras.id', 'solicitudcompras.cantidad_solicitada', 'solicitudcompras.estado')
            ->orderBy('solicitudcompras.created_at', 'desc')->paginate(3);
        }
        elseif ($request=='created_at' or $request=='cantidad_solicitada'){
            $solicitudes = Solicitudcompra::join('articulos','solicitudcompras.idarticulo','=','articulos.id')
            ->select('articulos.id as idarticulo','articulos.codigo as codigoarticulo','articulos.nombre','solicitudcompras.id', 'solicitudcompras.cantidad_solicitada', 'solicitudcompras.estado')
            ->where('solicitudcompras.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('solicitudcompras.created_at', 'desc')->paginate(3);
        }
        else {
            $solicitudes = Solicitudcompra::join('articulos','solicitudcompras.idarticulo','=','articulos.id')
            ->select('articulos.id as idarticulo','articulos.codigo as codigoarticulo','articulos.nombre','solicitudcompras.id', 'solicitudcompras.cantidad_solicitada', 'solicitudcompras.estado')
            ->where('articulos.'.$criterio, 'like', '%'. $buscar . '%')
            ->orderBy('solicitudcompras.created_at', 'desc')->paginate(3);
        }
        

        return [
            'pagination' => [
                'total'        => $solicitudes->total(),
                'current_page' => $solicitudes->currentPage(),
                'per_page'     => $solicitudes->perPage(),
                'last_page'    => $solicitudes->lastPage(),
                'from'         => $solicitudes->firstItem(),
                'to'           => $solicitudes->lastItem(),
            ],
            'solicitudes' => $solicitudes
        ];
    }
    public function store(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

        try{
            DB::beginTransaction();
        $cantidades = $request->cantidades;
        $idsarticulos = $request->idsarticulos;
        for ($i = 0; $i < sizeof($idsarticulos); $i++) {
            
            $solicitud = new Solicitudcompra();
            $solicitud->idarticulo = $idsarticulos[$i];
            $solicitud->cantidad_solicitada = $cantidades[$i];
            $solicitud->estado = "En Espera";
            $solicitud->save();
        
        }

            DB::commit();
        } catch (Exception $e){
            DB::rollBack();
        }

    }
    public function update(Request $request)
    {
        if (!$request->ajax()) return redirect('/');

            $solicitud = Solicitudcompra::findOrFail($request->id);
            $solicitud->estado = 'Resuelto';
            $solicitud->save();

    }
    
}
