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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            // Claves Foráneas
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict'); // Cliente
            $table->foreignId('payment_method_id')->nullable()->constrained('payment_methods')->onDelete('restrict');
            $table->foreignId('pickup_agency_id')->nullable()->constrained('pinchup_agencies')->onDelete('restrict');

            $table->string('order_number', 50)->unique()->autoIncrement()   ;
            $table->decimal('total_amount', 10, 2); // Monto total del pedido

            $table->enum('status', ['pending', 'processing', 'shipped', 'completed', 'canceled'])->default('pending'); // Estado del pedido

            // Información de Envío/Retiro (si se requiere detallar el envío)
            $table->string('shipping_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
