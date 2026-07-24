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
        Schema::create('productos', function (Blueprint $table) {
            $table->id(); // Identificador único del producto
            $table->unsignedBigInteger('id_categoria'); // Relación con la tabla categorías
            $table->string('codigo', 50)->unique()->nullable(); // Código de barras o SKU
            $table->string('nombre', 150); // Nombre del producto
            $table->text('descripcion')->nullable(); // Detalles técnicos o descripción
            $table->char('state', 1)->default('a'); // Estado para la eliminación lógica
            $table->timestamps();

            // Creación de la llave foránea
            $table->foreign('id_categoria', 'fk_productos_categorias')
                  ->references('id')
                  ->on('categorias');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
