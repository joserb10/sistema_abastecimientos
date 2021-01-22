<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeguimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seguimientos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_detalle_venta')->unsigned();
            $table->foreign('id_detalle_venta')->references('id')->on('detalle_ventas');
            $table->string('fecha_proceso_proveedor')->nullable();
            $table->string('fecha_proceso_almacen')->nullable();
            $table->string('fecha_proceso_inst_softw')->nullable();
            $table->string('fecha_entrega');
            $table->string('fecha_entrega_real')->nullable();
            $table->string('estado_entrega');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seguimientos');
    }
}
