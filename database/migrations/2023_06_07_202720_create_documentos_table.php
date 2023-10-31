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
        Schema::create('documentos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_requisicion');
            $table->unsignedBigInteger('tipo_requisicion');
            $table->string('ruta_documento', 255);
            $table->string('extension_documento', 10);
            $table->string('nombre_documento', 255);
            $table->bigInteger('tipo_documento');
            $table->timestamps('deleted_at');
            $table->timestamps();

            $table->foreign('tipo_documento')->references('id')->on('tipo_documentos');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentos');
    }
};