<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('categorias', function (Blueprint $table) {
            // Agregar columna empresa_id
            $table->unsignedBigInteger('empresa_id')->nullable()->after('id');
            
            // Agregar índice para mejorar rendimiento
            $table->index(['empresa_id', 'activo', 'orden']);
            
            // Agregar foreign key
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            
            // Quitar el índice único del slug si existe, ya que ahora será único por empresa
            $table->dropIndex('categorias_slug_unique');
            
            // Agregar índice único compuesto
            $table->unique(['empresa_id', 'slug']);
            $table->unique(['empresa_id', 'nombre']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categorias', function (Blueprint $table) {
            // Eliminar índices únicos compuestos
            $table->dropUnique(['empresa_id', 'slug']);
            $table->dropUnique(['empresa_id', 'nombre']);
            
            // Eliminar foreign key
            $table->dropForeign(['empresa_id']);
            
            // Eliminar índice
            $table->dropIndex(['empresa_id', 'activo', 'orden']);
            
            // Eliminar columna
            $table->dropColumn('empresa_id');
            
            // Restaurar índice único del slug
            $table->unique('slug');
        });
    }
};
