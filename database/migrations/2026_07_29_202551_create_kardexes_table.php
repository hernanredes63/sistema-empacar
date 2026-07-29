<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kardexs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_producto');
            $table->string('tipo_movimiento', 50); // 'Entrada' o 'Salida'
            $table->string('motivo', 100);       // 'Compra', 'Venta', 'Ajuste', etc.
            $table->integer('cantidad');
            $table->integer('stock_anterior');
            $table->integer('stock_actual');
            $table->decimal('precio_unitario', 12, 2)->default(0);
            $table->unsignedBigInteger('id_referencia')->nullable(); // ID de la venta o compra relacionada
            $table->char('state', 1)->default('a');
            $table->timestamps();

            $table->foreign('id_producto')->references('id')->on('productos');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kardexs');
    }
};