<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('seo', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('page_id');

            // Meta Tags Básicos
            $table->string('meta_title', 150)->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords', 500)->nullable();
            $table->string('canonical_url', 500)->nullable();
            $table->enum('robots', ['index,follow', 'noindex,follow', 'index,nofollow', 'noindex,nofollow'])->default('index,follow');

            // Open Graph (Facebook)
            $table->string('og_title', 150)->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image', 500)->nullable();
            $table->enum('og_type', ['website', 'article', 'product', 'business.business'])->default('website');
            $table->string('og_url', 500)->nullable();
            $table->string('og_site_name', 100)->nullable();

            // Twitter Cards
            $table->enum('twitter_card', ['summary', 'summary_large_image', 'app', 'player'])->default('summary_large_image');
            $table->string('twitter_title', 150)->nullable();
            $table->text('twitter_description')->nullable();
            $table->string('twitter_image', 500)->nullable();
            $table->string('twitter_site', 50)->nullable();
            $table->string('twitter_creator', 50)->nullable();

            // Schema.org JSON-LD
            $table->json('schema_markup')->nullable();

            // SEO Adicional
            $table->string('focus_keyword', 100)->nullable();
            $table->text('breadcrumb_title')->nullable();
            $table->boolean('sitemap_include')->default(true);
            $table->decimal('sitemap_priority', 2, 1)->default(0.8);
            $table->enum('sitemap_changefreq', ['always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never'])->default('weekly');

            // Estado y Analytics
            $table->boolean('is_active')->default(true);
            $table->integer('seo_score')->nullable();
            $table->text('seo_analysis')->nullable();

            $table->timestamps();

            // Foreign Key
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');

            // Índices
            $table->index('page_id');
            $table->index('is_active');
            $table->index('focus_keyword');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seo');
    }
};
