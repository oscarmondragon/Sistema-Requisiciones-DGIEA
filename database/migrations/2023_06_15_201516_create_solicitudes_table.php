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
        Schema::create('solicitudes', function (Blueprint $table) {
            $table->id();
            $table->string('clave_solicitud', 16);
            $table->unsignedBigInteger('tipo_requisicion');
            $table->unsignedBigInteger('id_proyecto');
            $table->unsignedBigInteger('id_rubro');
            $table->float('monto_total', 8, 2);
            $table->unsignedBigInteger('id_bitacora')->nullable();
            $table->boolean('vobo_admin')->nullable();
            $table->boolean('vobo_rt')->nullable();
            $table->boolean('obligo_comprobar')->nullable();
            $table->boolean('aviso_privacidad');
            $table->unsignedBigInteger('id_emisor');
            $table->unsignedBigInteger('id_revisor');
            $table->unsignedBigInteger('estatus_dgiea');
            $table->unsignedBigInteger('estatus_rt');
            $table->string('observaciones', 255)->nullable();
            $table->timestamps();

            //llaves foraneas
            $table->foreign('tipo_requisicion')->references('id')->on('tipo_requisiciones');
            $table->foreign('id_rubro')->references('id')->on('cuentas_contables');
            $table->foreign('id_revisor')->references('id')->on('users');




        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudes');
    }
};