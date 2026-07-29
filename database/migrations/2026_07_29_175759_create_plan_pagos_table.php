<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plan_pagos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_venta');
            $table->integer('cantidad_cuotas');
            $table->decimal('monto_cuota', 12, 2)->default(0);
            $table->decimal('total_deuda', 12, 2)->default(0);
            $table->decimal('saldo_pendiente', 12, 2)->default(0);
            $table->date('fecha_inicio')->nullable();
            $table->string('estado_plan', 50)->nullable();
            $table->char('state', 1)->default('a');
            $table->timestamps();

            // Clave foránea
            $table->foreign('id_venta')->references('id')->on('ventas');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_pagos');
    }
};