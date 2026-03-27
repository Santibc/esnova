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
    public function up()
    {
                Schema::create('precios_variantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('variante_producto_id')->constrained('variantes_productos')->onDelete('cascade');
            $table->foreignId('lista_precio_id')->constrained('listas_precios')->onDelete('cascade');
            $table->decimal('ajuste_precio', 8, 2)->default(0); // Ajuste sobre el precio base del producto
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            $table->unique(['variante_producto_id', 'lista_precio_id']);
            $table->index(['variante_producto_id', 'activo']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
