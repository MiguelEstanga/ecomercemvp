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
        Schema::table('orders', function (Blueprint $table) {

            $table->text('observaciones')->nullable();
            $table->string('imagen_documento')->nullable();

            $table->string('imagen_comprobante')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // IMPORTANTE: Define la acción inversa para hacer 'rollback'
            $table->dropColumn('observaciones');
            $table->dropColumn('imagen_documento');
            $table->dropColumn('imagen_comprobante');
        });
    }
};
