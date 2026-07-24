<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('categorias', function (Blueprint $table) {
        $table->id(); // Identificador único de la categoría
        $table->string('nombre', 150); // Nombre de la categoría[cite: 3]
        $table->string('descripcion', 255)->nullable(); // Descripción de la categoría[cite: 3]
        $table->char('state', 1)->default('a'); // Estado del registro[cite: 3]
        $table->timestamps(); // Fecha de creación y actualización[cite: 3]
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categorias');
    }
};
