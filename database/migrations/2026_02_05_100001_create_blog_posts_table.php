<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('blog_categoria_id')->nullable()->constrained('blog_categorias')->onDelete('set null');
            $table->string('titulo');
            $table->string('slug');
            $table->text('meta_description')->nullable();
            $table->string('imagen_portada')->nullable();
            $table->string('youtube_url')->nullable();
            $table->text('introduccion')->nullable();
            $table->longText('contenido')->nullable();
            $table->json('bloque_confianza')->nullable();
            $table->foreignId('producto_enlace_id')->nullable()->constrained('productos')->onDelete('set null');
            $table->json('faqs')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamp('publicado_en')->nullable();
            $table->integer('orden')->default(0);
            $table->timestamps();

            $table->unique(['empresa_id', 'slug']);
            $table->index(['empresa_id', 'activo', 'publicado_en']);
            $table->index('blog_categoria_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('blog_posts');
    }
};
