<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->string('seo_title')->nullable()->after('slug');
            $table->string('seo_keywords')->nullable()->after('meta_description');
            $table->string('og_image')->nullable()->after('seo_keywords');
            $table->string('canonical_url')->nullable()->after('og_image');
            $table->boolean('noindex')->default(false)->after('canonical_url');
            $table->boolean('nofollow')->default(false)->after('noindex');
        });
    }

    public function down()
    {
        Schema::table('blog_posts', function (Blueprint $table) {
            $table->dropColumn([
                'seo_title',
                'seo_keywords',
                'og_image',
                'canonical_url',
                'noindex',
                'nofollow',
            ]);
        });
    }
};
