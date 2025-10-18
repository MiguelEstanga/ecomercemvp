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
        Schema::create('product_imagens', function (Blueprint $table) {
            $table->id();
            // Clave Foránea a la tabla products
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('path'); // Ruta o URL de la imagen
            $table->boolean('is_main')->default(false); // Imagen principal para la vista previa
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_imagens');
    }
};
