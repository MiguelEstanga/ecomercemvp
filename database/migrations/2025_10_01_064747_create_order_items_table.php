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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            // Clave Foránea a la tabla orders
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            // Clave Foránea al producto (aunque el producto cambie después, esta es la referencia)
            $table->foreignId('product_id')->constrained('products')->onDelete('restrict');

            $table->string('product_name'); // Guardar el nombre para historico
            $table->decimal('unit_price', 8, 2); // Precio al que se vendió
            $table->unsignedInteger('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
