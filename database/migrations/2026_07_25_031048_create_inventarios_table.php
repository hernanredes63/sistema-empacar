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
    Schema::create('inventarios', function (Blueprint $table) {
        $table->id(); // Identificador único del movimiento[cite: 2]
        $table->foreignId('id_producto')->constrained('productos'); // Producto relacionado[cite: 2]
        $table->string('tipo_movimiento', 50); // Entrada, salida o ajuste[cite: 2]
        $table->integer('cantidad')->default(0); // Cantidad del movimiento[cite: 2]
        $table->integer('stock_actual')->nullable()->default(0); // Stock resultante[cite: 2]
        $table->timestamp('fecha_movimiento')->nullable(); // Fecha del movimiento[cite: 2]
        $table->string('descripcion', 255)->nullable(); // Observación del movimiento[cite: 2]
        $table->string('origen_tipo', 50)->nullable(); // Origen del movimiento (compra, venta, etc.)[cite: 2]
        $table->bigInteger('origen_id')->nullable(); // ID del registro origen[cite: 2]
        $table->char('state', 1)->default('a'); // Estado del movimiento[cite: 2]
        $table->timestamps(); // created_at y updated_at[cite: 2]
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventarios');
    }
};
