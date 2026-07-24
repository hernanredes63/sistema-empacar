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
    Schema::create('clientes', function (Blueprint $table) {
        $table->id(); // Identificador único del cliente
        $table->unsignedBigInteger('id_user')->nullable(); // Usuario relacionado al cliente, si corresponde
        $table->string('nombre', 150); // Nombre o razón social del cliente[cite: 3]
        $table->string('documento', 50)->nullable(); // CI, NIT u otro documento del cliente[cite: 3]
        $table->string('telefono', 50)->nullable(); // Teléfono del cliente[cite: 3]
        $table->string('email', 150)->nullable(); // Correo electrónico del cliente[cite: 3]
        $table->string('direccion', 255)->nullable(); // Dirección del cliente[cite: 3]
        $table->string('ciudad', 100)->nullable(); // Ciudad donde se encuentra el cliente[cite: 3]
        $table->char('state', 1)->default('a'); // Estado del registro[cite: 3]
        $table->timestamps(); // Fecha de creación y actualización[cite: 3]

        // Clave foránea según el diseño físico[cite: 3]
        $table->foreign('id_user', 'fk_clientes_users')
              ->references('id')
              ->on('users');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
