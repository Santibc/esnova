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
        Schema::create('items_compra', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('compra_id');
            $table->unsignedBigInteger('producto_id');
            $table->unsignedBigInteger('variante_producto_id')->nullable();
            $table->integer('cantidad');
            $table->decimal('precio_unitario', 10, 2);
            $table->decimal('descuento', 10, 2)->default(0);
            $table->decimal('precio_total', 12, 2);
            $table->string('referencia_producto');
            $table->string('nombre_producto');
            $table->string('info_variante')->nullable();
            $table->timestamps();
            
            $table->foreign('compra_id')->references('id')->on('compras')->onDelete('cascade');
            $table->foreign('producto_id')->references('id')->on('productos')->onDelete('cascade');
            $table->foreign('variante_producto_id')->references('id')->on('variantes_productos')->onDelete('cascade');
            $table->index('compra_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('items_compra');
    }
};
