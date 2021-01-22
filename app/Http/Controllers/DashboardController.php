<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\{DetalleVenta, Observacion, Seguimiento, DetalleIngreso, Venta};

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $anio=date('Y');
        $ingresos=DB::table('ingresos as i')
        ->select(DB::raw('MONTH(i.fecha_hora) as mes'),
        DB::raw('YEAR(i.fecha_hora) as anio'),
        DB::raw('SUM(i.total) as total'))
        ->whereYear('i.fecha_hora',$anio)
        ->groupBy(DB::raw('MONTH(i.fecha_hora)'),DB::raw('YEAR(i.fecha_hora)'))
        ->get();

        $ventas=DB::table('ventas as v')
        ->select(DB::raw('MONTH(v.fecha_hora) as mes'),
        DB::raw('YEAR(v.fecha_hora) as anio'),
        DB::raw('SUM(v.total) as total'))
        ->whereYear('v.fecha_hora',$anio)
        ->groupBy(DB::raw('MONTH(v.fecha_hora)'),DB::raw('YEAR(v.fecha_hora)'))
        ->get();

        return ['ingresos'=>$ingresos,'ventas'=>$ventas,'anio'=>$anio];      

    }

    public function reportes(Request $request)
    {
        $num_ventas_totales = Venta::all()->count();
        // Ventas Pre - Noviembre
        $num_ventas_pre = Venta::where('fecha_hora', 'like', '2020-11%')->get()->count();
        // Ventas diciembre, enero, febrero
        $num_ventas_dic = Venta::where('fecha_hora', 'like', '2020-12%')->get()->count();
        $num_ventas_ene = Venta::where('fecha_hora', 'like', '2021-01%')->get()->count();
        $num_ventas_feb = Venta::where('fecha_hora', 'like', '2021-02%')->get()->count();
        // Ventas Post
        $num_ventas_post = $num_ventas_dic + $num_ventas_ene + $num_ventas_feb;

        $num_observaciones = Observacion::all()->count();


        $num_observaciones_pre = Observacion::join('detalle_ventas', 'observaciones.detalle_venta_id', 'detalle_ventas.id')
                            ->join('ventas', 'detalle_ventas.idventa', 'ventas.id')
                            ->select('ventas.id')
                            ->whereRaw('fecha_hora like "2020-11%"')
                            ->groupBy('ventas.id')
                            ->get()
                            ->count();

        $num_observaciones_post = Observacion::join('detalle_ventas', 'observaciones.detalle_venta_id', 'detalle_ventas.id')
                            ->join('ventas', 'detalle_ventas.idventa', 'ventas.id')
                            ->select('ventas.id')
                            ->whereRaw('fecha_hora like "2020-12%" or fecha_hora like "2021-01%" or fecha_hora like "2021-02%"')
                            ->groupBy('ventas.id')
                            ->get()
                            ->count();
 
        $num_ventas_no_entregadas_a_tiempo_pre = Seguimiento::join('detalle_ventas', 'seguimientos.id_detalle_venta', 'detalle_ventas.id')
                            ->join('ventas', 'detalle_ventas.idventa', 'ventas.id')
                            ->select('ventas.id')
                            ->whereRaw('fecha_entrega != fecha_entrega_real and fecha_hora like "2020-11%"')
                            ->groupBy('ventas.id')
                            ->get()
                            ->count();

        $num_ventas_no_entregadas_a_tiempo_post = Seguimiento::join('detalle_ventas', 'seguimientos.id_detalle_venta', 'detalle_ventas.id')
                            ->join('ventas', 'detalle_ventas.idventa', 'ventas.id')
                            ->select('ventas.id')
                            ->whereRaw('fecha_entrega != fecha_entrega_real and fecha_hora like "2020-12%"')
                            ->orWhereRaw('fecha_entrega != fecha_entrega_real and fecha_hora like "2021-01%"')
                            ->orWhereRaw('fecha_entrega != fecha_entrega_real and fecha_hora like "2021-02%"')
                            ->groupBy('ventas.id')
                            ->get()
                            ->count();

        // Entregas a tiempo
        ($num_ventas_totales - $num_ventas_no_entregadas_a_tiempo_pre) / $num_ventas_totales;

        // Entregas perfectas
        ($num_ventas_totales - $num_observaciones_pre) / $num_ventas_totales; 

        // Entregas perfectas_pre
        // $array_prefectas_pre = array($num_ventas_pre-$num_observaciones_pre, $num_observaciones_pre);
        $array_prefectas_pre = array($num_ventas_pre, $num_observaciones_pre);
        // Entregas perfectas_post
        // $array_prefectas_post = array($num_ventas_post-$num_observaciones_post, $num_observaciones_post);
        $array_prefectas_post = array($num_ventas_post, $num_observaciones_post);

        // Entregas a_tiempo_pre
        // $array_atiempo_pre = array($num_ventas_pre-$num_ventas_no_entregadas_a_tiempo_pre, $num_ventas_no_entregadas_a_tiempo_pre);
        $array_atiempo_pre = array($num_ventas_pre, $num_ventas_no_entregadas_a_tiempo_pre);
        // Entregas a_tiempo_post
        // $array_atiempo_post = array($num_ventas_post-$num_ventas_no_entregadas_a_tiempo_post, $num_ventas_no_entregadas_a_tiempo_post);
        $array_atiempo_post = array($num_ventas_post, $num_ventas_no_entregadas_a_tiempo_post);

        // rotación de inventarios
        $cantidades_precios = DetalleIngreso::select('detalle_ingresos.precio', 'detalle_ingresos.cantidad')
                            ->get();
        $total_ventas = 0;
        
        foreach($cantidades_precios as $det=>$cant_prec){
            $total_ventas += $cant_prec->precio * $cant_prec->cantidad;     
        }

        $existencias_promedio = 400;
        $rotacion_inventarios = $total_ventas/$existencias_promedio;
        
        // rotación de inventarios pre
        $cantidades_unidad_pre = DetalleVenta::join('ventas', 'detalle_ventas.idventa', 'ventas.id')
                                    ->selectRaw('sum(cantidad) as cantidades_vendidas')
                                    ->whereRaw('fecha_hora like "2020-11%"')
                                    ->first();
        $cantidades_vendidas_pre = intval($cantidades_unidad_pre['cantidades_vendidas']);
        $inventario_prom_pre = 22*25;
        $rotacion_inventarios_pre = $cantidades_vendidas_pre / $inventario_prom_pre;

        // rotación de inventarios post
        $cantidades_unidad_post_dic = DetalleVenta::join('ventas', 'detalle_ventas.idventa', 'ventas.id')
                                    ->selectRaw('sum(cantidad) as cantidades_vendidas')
                                    ->whereRaw('fecha_hora like "2020-12%"')
                                    ->first();
        $cantidades_vendidas_post_dic = intval($cantidades_unidad_post_dic['cantidades_vendidas']);

        $cantidades_unidad_post_ene = DetalleVenta::join('ventas', 'detalle_ventas.idventa', 'ventas.id')
                                    ->selectRaw('sum(cantidad) as cantidades_vendidas')
                                    ->whereRaw('fecha_hora like "2021-01%"')
                                    ->first();
        $cantidades_vendidas_post_ene = intval($cantidades_unidad_post_ene['cantidades_vendidas']);

        $cantidades_unidad_post_feb = DetalleVenta::join('ventas', 'detalle_ventas.idventa', 'ventas.id')
                                    ->selectRaw('sum(cantidad) as cantidades_vendidas')
                                    ->whereRaw('fecha_hora like "2021-02%"')
                                    ->first();
        $cantidades_vendidas_post_feb = intval($cantidades_unidad_post_feb['cantidades_vendidas']);

        $inventario_prom_post_dic = 10*25;
        $inventario_prom_post_ene = 9*25;
        $inventario_prom_post_feb = 5*25;

        $rotacion_inventarios_post_dic = $cantidades_vendidas_post_dic / $inventario_prom_post_dic;
        $rotacion_inventarios_post_ene = $cantidades_vendidas_post_ene / $inventario_prom_post_ene;
        $rotacion_inventarios_post_feb = $cantidades_vendidas_post_feb / $inventario_prom_post_feb;

        
        return [
            'num_ventas_totales' => $num_ventas_totales,
            'num_ventas_pre' => $num_ventas_pre,
            'num_ventas_dic' => $num_ventas_dic,
            'num_ventas_ene' => $num_ventas_ene,
            'num_ventas_feb' => $num_ventas_feb,
            'num_ventas_post' => $num_ventas_post,
            'num_observaciones' => $num_observaciones,
            'num_observaciones_pre' => $num_observaciones_pre,
            'num_observaciones_post' => $num_observaciones_post,
            'num_ventas_no_entregadas_a_tiempo_pre' => $num_ventas_no_entregadas_a_tiempo_pre,
            'num_ventas_no_entregadas_a_tiempo_post' => $num_ventas_no_entregadas_a_tiempo_post,
            'total_ventas' => $total_ventas,
            'rotacion_inventarios' => $rotacion_inventarios,
            // 'cantidad_unidades_pre' => $cantidades_unidad_pre,
            'cantidad_vendidas_pre' => $cantidades_vendidas_pre,
            'cantidad_vendidas_post_dic' => $cantidades_vendidas_post_dic,
            'cantidad_vendidas_post_ene' => $cantidades_vendidas_post_ene,
            'cantidad_vendidas_post_feb' => $cantidades_vendidas_post_feb,
            'array_perfectas_pre' => $array_prefectas_pre,
            'array_perfectas_post' => $array_prefectas_post,
            'array_atiempo_pre' => $array_atiempo_pre,
            'array_atiempo_post' => $array_atiempo_post,
            'rotacion_pre' => $rotacion_inventarios_pre,
            'rotacion_post_dic' => $rotacion_inventarios_post_dic,
            'rotacion_post_ene' => $rotacion_inventarios_post_ene,
            'rotacion_post_feb' => $rotacion_inventarios_post_feb,
            'inv_prom_pre' => $inventario_prom_pre,
            'inv_prom_post_dic' => $inventario_prom_post_dic,
            'inv_prom_post_ene' => $inventario_prom_post_ene,
            'inv_prom_post_feb' => $inventario_prom_post_feb,
        ];
    }
}
