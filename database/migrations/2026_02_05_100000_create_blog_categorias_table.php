<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('blog_categorias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade');
            $table->string('nombre');
            $table->string('slug');
            $table->text('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->integer('orden')->default(0);
            $table->timestamps();

            $table->unique(['empresa_id', 'slug']);
            $table->index(['empresa_id', 'activo', 'orden']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('blog_categorias');
    }
};
