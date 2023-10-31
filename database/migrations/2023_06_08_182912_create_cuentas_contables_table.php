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
        Schema::create('cuentas_contables', function (Blueprint $table) {
            $table->id();
            $table->integer('id_cuenta');
            $table->string('nombre_cuenta');
            $table->integer('tipo_requisicion');
            $table->int('id_especial');
            $table->boolean('estatus');
            $table->integer('id_usuario_sesion', 20);
            $table->timestamps();

            $table->foreign('tipo_requisicion')->references('id')->on('tipo_requisiciones');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuentas_contables');
    }
};