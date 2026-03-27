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
        Schema::create('imagenes_productos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            $table->string('ruta_imagen');
            $table->string('texto_alternativo')->nullable();
            $table->integer('orden')->default(0); // Para ordenar las imágenes en el carrusel
            $table->boolean('es_principal')->default(false); // Imagen que aparece primero en listados
            $table->timestamps();
            
            $table->index(['producto_id', 'orden']);
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
