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
        Schema::create('templates_tienda', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique()->comment('Identificador único del template (ej: default, brasilia, minimal)');
            $table->string('nombre')->comment('Nombre descriptivo del template');
            $table->text('descripcion')->nullable()->comment('Descripción del template');
            $table->string('vista_index')->comment('Ruta de la vista index (ej: tienda.index)');
            $table->string('vista_categoria')->comment('Ruta de la vista de categorías');
            $table->string('vista_producto')->comment('Ruta de la vista de producto');
            $table->string('layout')->comment('Ruta del layout base (ej: tienda.layout)');
            $table->string('preview_image')->nullable()->comment('Imagen de preview del template');
            $table->boolean('activo')->default(true)->comment('Estado del template');
            $table->json('configuracion')->nullable()->comment('Configuración adicional (colores, fuentes, etc)');
            $table->integer('orden')->default(0)->comment('Orden de visualización');
            $table->boolean('es_default')->default(false)->comment('Indica si es el template por defecto');
            $table->timestamps();

            $table->index(['activo', 'orden']);
            $table->index('es_default');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('templates_tienda');
    }
};
