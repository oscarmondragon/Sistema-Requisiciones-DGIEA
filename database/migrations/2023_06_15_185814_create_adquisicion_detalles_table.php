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
        Schema::create('adquisicion_detalles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_adquisicion');
            $table->string('descripcion', 255);
            $table->float('importe_cotizacion', 8, 2);
            $table->string('justificacion_software', 255);
            $table->integer('alumnos');
            $table->integer('profesores_invest');
            $table->integer('administrativos');
            $table->unsignedBigInteger('estatus_dgiea');
            $table->unsignedBigInteger('estatus_rt');
            $table->string('observaciones', 255);
            $table->timestamps();

            $table->foreign('id_adquisicion')->references('id')->on('adquisiciones');
            $table->foreign('estatus_dgiea')->references('id')->on('estatus_requisiciones');
            $table->foreign('estatus_rt')->references('id')->on('estatus_requisiciones');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adquisicion_detalles');
    }
};