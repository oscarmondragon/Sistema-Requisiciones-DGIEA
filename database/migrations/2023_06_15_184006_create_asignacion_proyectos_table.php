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
        Schema::create('asignacion_proyectos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_proyecto');
            $table->unsignedBigInteger('id_revisor');
            $table->timestamps();

            $table->foreign('id_revisor')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignacion_proyectos');
    }
};