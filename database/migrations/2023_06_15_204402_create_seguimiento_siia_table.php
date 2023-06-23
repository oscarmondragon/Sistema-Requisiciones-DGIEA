<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('seguimiento_siia', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_requisicion');
            $table->unsignedBigInteger('id_requisicion_siia');
            $table->float('monto_requisicion', 8, 2);
            $table->date('fecha_ingreso_drm_dou');
            $table->integer('num_partidas');
            $table->integer('estatus_rt');
            $table->integer('estatus_dgiea');
            $table->string('motivo_rechazo', 255);
            $table->integer('num_facturas');
            $table->date('fecha_recepcion_fact');
            $table->float('monto_factura', 8, 2);
            $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seguimiento_siia');
    }
};