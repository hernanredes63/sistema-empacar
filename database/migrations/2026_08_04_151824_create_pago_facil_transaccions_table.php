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
        Schema::create('pago_facil_transaccions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_venta')->nullable();
            $table->unsignedBigInteger('id_cuota')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('payment_number')->unique();
            $table->decimal('monto', 10, 2);
            $table->string('estado')->default('generado');
            $table->text('qr_url')->nullable();
            $table->longText('qr_base64')->nullable();
            $table->json('request_json')->nullable();
            $table->json('response_json')->nullable();
            $table->timestamp('fecha_creacion')->nullable();
            $table->timestamp('fecha_actualizacion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pago_facil_transaccions');
    }
};
