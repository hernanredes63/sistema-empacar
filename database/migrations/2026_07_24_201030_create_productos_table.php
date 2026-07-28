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
        $table->id();
        $table->string('nombre', 150);
        $table->text('descripcion')->nullable();
        $table->string('codigo', 100)->unique()->nullable();
        
        // --- COLUMNAS FALTANTES QUE CAUSAN EL ERROR ---
        $table->decimal('precio_compra', 12, 2)->default(0);
        $table->decimal('precio_venta', 12, 2)->default(0);
        $table->integer('stock_actual')->default(0);
        $table->integer('stock_minimo')->default(0);
        // ----------------------------------------------

        $table->unsignedBigInteger('id_categoria')->nullable();
        $table->string('imagen', 255)->nullable();
        $table->char('state', 1)->default('a');
        $table->timestamps();

        // Relación con categorías
        $table->foreign('id_categoria')
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
