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
    Schema::create('proveedores', function (Blueprint $table) {
        $table->id(); // Identificador único del proveedor
        $table->string('nombre', 150); // Nombre o razón social[cite: 3]
        $table->string('nit', 50)->unique()->nullable(); // Número de identificación tributaria[cite: 3]
        $table->string('telefono', 50)->nullable(); // Teléfono del proveedor[cite: 3]
        $table->string('email', 150)->nullable(); // Correo electrónico[cite: 3]
        $table->string('direccion', 255)->nullable(); // Dirección del proveedor[cite: 3]
        $table->text('descripcion')->nullable(); // Información adicional[cite: 3]
        $table->char('state', 1)->default('a'); // Estado del proveedor[cite: 3]
        $table->timestamps(); // Fecha de creación y actualización[cite: 3]
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proveedors');
    }
};
