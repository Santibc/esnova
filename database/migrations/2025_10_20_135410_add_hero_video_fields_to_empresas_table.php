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
        Schema::table('empresas', function (Blueprint $table) {
            $table->string('hero_video_url')->nullable()->after('template_tienda_id')->comment('URL del video hero para template Brasilia');
            $table->string('hero_video_message', 500)->nullable()->after('hero_video_url')->comment('Mensaje del video hero');
            $table->string('hero_video_button_text', 100)->nullable()->after('hero_video_message')->comment('Texto del botón del video hero');
            $table->string('hero_video_button_link')->nullable()->after('hero_video_button_text')->comment('Link del botón del video hero');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropColumn(['hero_video_url', 'hero_video_message', 'hero_video_button_text', 'hero_video_button_link']);
        });
    }
};
