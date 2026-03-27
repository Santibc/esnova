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
        Schema::create('variantes_productos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            $table->string('talla')->nullable(); // Talla
            $table->string('color')->nullable(); // Color
            $table->string('sku')->unique(); // SKU único para cada variante
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            $table->index(['producto_id', 'activo']);
            $table->unique(['producto_id', 'talla', 'color']);
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
