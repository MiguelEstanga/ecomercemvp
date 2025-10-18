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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150)->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 8, 2); // Precio de venta
            $table->unsignedInteger('stock'); // Stock disponible
            $table->boolean('is_active')->default(true); // Para activar/desactivar el producto
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
