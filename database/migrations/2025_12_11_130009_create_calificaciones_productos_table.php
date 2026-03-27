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
        Schema::create('calificaciones_productos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('compra_id')->constrained('compras')->onDelete('cascade');
            $table->foreignId('item_compra_id')->constrained('items_compra')->onDelete('cascade');
            $table->tinyInteger('estrellas')->unsigned(); // 1-5
            $table->string('titulo')->nullable();
            $table->text('comentario')->nullable();
            $table->boolean('verificada')->default(true); // Compra verificada
            $table->boolean('aprobada')->default(true); // Moderación
            $table->timestamps();

            // Un usuario solo puede calificar un producto de una compra específica una vez
            $table->unique(['user_id', 'producto_id', 'compra_id'], 'calificacion_unica');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calificaciones_productos');
    }
};
