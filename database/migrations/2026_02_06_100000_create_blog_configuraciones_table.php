<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('blog_configuraciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade');

            // Banner
            $table->string('banner_titulo')->nullable();
            $table->string('banner_subtitulo')->nullable();
            $table->string('banner_imagen')->nullable();

            // SEO
            $table->string('seo_title', 70)->nullable();
            $table->text('meta_description')->nullable();
            $table->string('seo_keywords', 500)->nullable();
            $table->string('canonical_url', 500)->nullable();
            $table->string('og_image')->nullable();
            $table->boolean('noindex')->default(false);
            $table->boolean('nofollow')->default(false);

            $table->timestamps();

            $table->unique('empresa_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('blog_configuraciones');
    }
};
