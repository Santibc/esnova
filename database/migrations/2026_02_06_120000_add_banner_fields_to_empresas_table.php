<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->string('banner_titulo')->nullable()->after('hero_video_button_link');
            $table->string('banner_subtitulo', 500)->nullable()->after('banner_titulo');
            $table->string('banner_imagen')->nullable()->after('banner_subtitulo');
            $table->string('banner_btn1_texto')->nullable()->after('banner_imagen');
            $table->string('banner_btn1_link')->nullable()->after('banner_btn1_texto');
            $table->string('banner_btn2_texto')->nullable()->after('banner_btn1_link');
            $table->string('banner_btn2_link')->nullable()->after('banner_btn2_texto');
        });
    }

    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropColumn([
                'banner_titulo',
                'banner_subtitulo',
                'banner_imagen',
                'banner_btn1_texto',
                'banner_btn1_link',
                'banner_btn2_texto',
                'banner_btn2_link',
            ]);
        });
    }
};
