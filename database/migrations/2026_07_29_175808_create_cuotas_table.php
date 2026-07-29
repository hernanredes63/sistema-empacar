<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cuotas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_plan_pago');
            $table->integer('numero_cuota');
            $table->date('fecha_vencimiento');
            $table->decimal('monto', 12, 2)->default(0);
            $table->string('estado_cuota', 50)->nullable();
            $table->date('fecha_pago')->nullable();
            $table->string('pagofacil_transaction_id', 255)->nullable();
            $table->char('state', 1)->default('a');
            $table->timestamps();

            // Clave foránea
            $table->foreign('id_plan_pago')->references('id')->on('plan_pagos');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cuotas');
    }
};