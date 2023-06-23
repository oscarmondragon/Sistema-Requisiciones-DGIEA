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
            $table->integer('clave_cuenta');
            $table->string('nombre_cuenta', 120);
            $table->unsignedBigInteger('tipo_requisicion');
            $table->boolean('estatus');
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