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
    Schema::create('detalle_compras', function (Blueprint $table) {
        $table->id();
        // Relaciones principales
        $table->foreignId('id_compra')->constrained('compras');
        $table->foreignId('id_producto')->constrained('productos');
        
        $table->integer('cantidad');
        $table->decimal('precio_compra', 12, 2)->default(0);
        $table->decimal('subtotal', 12, 2)->default(0);
        $table->char('state', 1)->default('a');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_compras');
    }
};
