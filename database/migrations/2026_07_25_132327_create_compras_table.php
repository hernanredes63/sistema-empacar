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
    Schema::create('compras', function (Blueprint $table) {
        $table->id();
        $table->date('fecha_compra');
        // Relación con proveedores
        $table->foreignId('id_proveedor')->constrained('proveedores');
        $table->decimal('total', 12, 2)->default(0);
        $table->string('estado_compra', 50)->nullable();
        $table->text('observacion')->nullable();
        $table->char('state', 1)->default('a');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compras');
    }
};
