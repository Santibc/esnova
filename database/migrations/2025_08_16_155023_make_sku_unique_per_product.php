<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('variantes_productos', function (Blueprint $table) {
            // Índice actual en tu dump: variantes_productos_sku_unique
            $table->dropUnique('variantes_productos_sku_unique');
            // Nuevo índice: sku único dentro del producto
            $table->unique(['producto_id', 'sku'], 'variantes_producto_sku_unique');
        });
    }

    public function down(): void
    {
        Schema::table('variantes_productos', function (Blueprint $table) {
            $table->dropUnique('variantes_producto_sku_unique');
            $table->unique('sku', 'variantes_productos_sku_unique');
        });
    }

};
