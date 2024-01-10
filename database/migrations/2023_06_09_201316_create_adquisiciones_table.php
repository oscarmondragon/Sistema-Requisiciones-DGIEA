<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Proyecto;


return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('adquisiciones', function (Blueprint $table) {
            $table->id();
            $table->string('clave_adquisicion', 16);
            $table->unsignedBigInteger('tipo_requisicion');
            $table->int('clave_proyecto', 20);
            $table->int('clave_espacio_academico', 20);
            $table->int('clave_rt', 20);
            $table->string('tipo_financiamiento', 50);
            $table->unsignedBigInteger('id_rubro');
            $table->boolean('afecta_investigacion');
            $table->string('justificacion_academica', 255)->nullable();
            $table->boolean('exclusividad')->nullable();
            $table->unsignedBigInteger('id_carta_exclusividad')->nullable();
            $table->boolean('vobo_admin')->nullable();
            $table->boolean('vobo_rt')->nullable();
            $table->unsignedBigInteger('id_emisor');
            $table->unsignedBigInteger('id_revisor');
            $table->unsignedBigInteger('estatus_general');
            $table->string('observaciones', 255)->nullable();
            $table->float('subtotal', 10.2);
            $table->float('iva', 10.2);
            $table->float('total', 10.2);
            $table->timestamp('deleted_at');
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
        Schema::dropIfExists('adquisiciones');
    }
};