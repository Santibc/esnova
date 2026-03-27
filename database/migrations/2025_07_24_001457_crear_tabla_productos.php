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
       Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('referencia')->unique(); // Referencia
            $table->string('nombre'); // Nombre del producto
            $table->text('descripcion')->nullable();
            $table->string('unidad_venta'); // Unidad de venta
            $table->string('unidad_empaque'); // Unidad de empaque
            $table->string('extension')->nullable(); // Color o Motivo
            
            $table->foreignId('categoria_id')->constrained('categorias')->onDelete('cascade');
            $table->boolean('activo')->default(true);
            $table->boolean('tiene_variantes')->default(false); // Para productos con tallas/colores
            $table->timestamps();
            
            $table->index(['activo', 'categoria_id']);
            $table->index('referencia');
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
