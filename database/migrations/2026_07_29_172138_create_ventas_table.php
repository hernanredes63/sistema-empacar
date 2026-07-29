<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_venta');
            $table->unsignedBigInteger('id_cliente');
            $table->decimal('total', 12, 2)->default(0);
            $table->string('tipo_venta', 50)->nullable();
            $table->string('estado_venta', 50)->nullable();
            $table->text('observacion')->nullable();
            $table->string('pagofacil_transaction_id', 255)->nullable();
            $table->char('state', 1)->default('a');
            $table->timestamps();

            // Clave foránea
            $table->foreign('id_cliente')->references('id')->on('clientes');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};