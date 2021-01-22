<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Venta;
use App\Cliente;
use App\Requerimiento;
use App\Persona;
use App\Articulo;
use App\DetalleVenta;
use App\Observacion;
use App\Seguimiento;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    // public function import()
    // {
    //     Excel::load('ventas.csv', function($reader) {
    //          foreach ($reader->get() as $request_venta) {
    //             // $mytime= Carbon::now('America/Lima');

    //             $venta = new Venta();
    //             $venta->idcliente = $request_venta->idcliente;
    //             $venta->idusuario = $request_venta->idusuario;
    //             $venta->tipo_comprobante = $request_venta->tipo_comprobante;
    //             $venta->serie_comprobante = $request_venta->serie_comprobante;
    //             $venta->num_comprobante = $request_venta->num_comprobante;
    //             $venta->fecha_hora = $request_venta->fecha_hora;
    //             $venta->impuesto = $request_venta->impuesto;
    //             $venta->total = $request_venta->total;
    //             $venta->estado = $request_venta->estado;
    //             $venta->save();

    //           }
    //     });
    //     return Venta::all();
    // }

    function csvToArray($filename = '', $delimiter = ';')
    {
       if (!file_exists($filename) || !is_readable($filename))
           return false;

       $header = null;
       $data = array();
       if (($handle = fopen($filename, 'r')) !== false)
       {
           while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
           {
               if (!$header)
                   $header = $row;
               else
                   $data[] = array_combine($header, $row);
           }
           fclose($handle);
       }

       return $data;
    }

    public function importCsv_ventas()
    {
       $file = public_path('/ventas_pre.csv');

       $ventasArry = $this->csvToArray($file);

       for ($i = 0; $i < count($ventasArry); $i ++)
       {
            $venta = new Venta();
            $venta->idcliente = $ventasArry[$i]['idcliente'];
            $venta->idusuario = $ventasArry[$i]['idusuario'];
            $venta->tipo_comprobante = $ventasArry[$i]['tipo_comprobante'];
            $venta->serie_comprobante = $ventasArry[$i]['serie_comprobante'];
            $venta->num_comprobante = $ventasArry[$i]['num_comprobante'];
            $venta->fecha_hora = $ventasArry[$i]['fecha_hora'];

            // Find Articulo para detalle y seguimiento
            $articulo = Articulo::findOrFail($ventasArry[$i]['idarticulo']);
            
            $venta->impuesto = $ventasArry[$i]['impuesto'];
            $venta->total = $articulo->precio_venta * $ventasArry[$i]['cantidad'];
            $venta->estado = $ventasArry[$i]['estado'];
            $venta->save();

            // Creación requermiento
            if($ventasArry[$i]['requerimiento'] and $ventasArry[$i]['descripcion_req']){
                $requerimiento = new Requerimiento();
                $requerimiento->idventa = $venta->id;
                $requerimiento->requerimiento = $ventasArry[$i]['requerimiento'];
                $requerimiento->descripcion = $ventasArry[$i]['descripcion_req'];
                $requerimiento->tiempo_inst_softw = $ventasArry[$i]['tiempo_inst_softw'];
                $requerimiento->save();
            }

            // Creación detalle
            $detalle = new DetalleVenta();
            $detalle->idventa = $venta->id;
            $detalle->idarticulo = $ventasArry[$i]['idarticulo'];
            $detalle->cantidad = $ventasArry[$i]['cantidad'];
            $detalle->precio = $articulo->precio_venta;
            $detalle->descuento = $ventasArry[$i]['descuento'];         
            $detalle->save();

            // Creación observación
            if($ventasArry[$i]['observacion']){
                $observacion = new Observacion();
                $observacion->detalle_venta_id = $detalle->id;
                $observacion->descripcion = $ventasArry[$i]['observacion'];
                $observacion->estado = 'Resuelto';
                $observacion->save();
            }            

            // Find Articulo para detalle y seguimiento
            $articulo = Articulo::findOrFail($ventasArry[$i]['idarticulo']);

            // Creación de seguimiento
            $seguimiento = new Seguimiento();
            $seguimiento->id_detalle_venta = $detalle->id;

            if($ventasArry[$i]['fecha_proceso_proveedor']=='si'){
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

            $requerimiento = Requerimiento::where('idventa', '=',$detalle->idventa)->get();
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

            if($ventasArry[$i]['fecha_entrega_real']=="1"){
                $añadido_random = rand(1,4);
                $seguimiento->fecha_entrega_real = strtotime($añadido_random .' day', strtotime($seguimiento->fecha_entrega));
                $seguimiento->fecha_entrega_real = date('d-m-Y', $seguimiento->fecha_entrega_real);
            }else{
                $seguimiento->fecha_entrega_real = $seguimiento->fecha_entrega;
            }
            $seguimiento->estado_entrega = 'Entregado';     
            $seguimiento->save();
             
       }

       return Venta::all();    
    }

    public function importCsv_clientes()
    {
       $file = public_path('/clientes_x.csv');

       $clientesArry = $this->csvToArray($file);

       for ($i = 0; $i < count($clientesArry); $i ++)
       {
            $persona = new Persona();
            $persona->nombre = $clientesArry[$i]['nombre'];
            $persona->tipo_documento = $clientesArry[$i]['tipo_documento'];
            $persona->num_documento = $clientesArry[$i]['num_documento'];
            $persona->direccion = $clientesArry[$i]['direccion'];
            $persona->telefono = $clientesArry[$i]['telefono'];
            $persona->email = $clientesArry[$i]['email'];
            $persona->save();

            $cliente = new Cliente();
            $cliente->idpersona = $persona->id;
            $cliente->created_at = $persona->created_at;
            $cliente->updated_at = $persona->updated_at;
            $cliente->save();
       }

       return Cliente::all();    
    }
}
