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
        Schema::create('solicitud_detalles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_solicitud');
            $table->string('descripcion', 255);
            $table->float('importe_cotizacion', 8, 2);

            $table->timestamps();

            $table->foreign('id_solicitud')->references('id')->on('solicitudes');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitud_detalles');
    }
};