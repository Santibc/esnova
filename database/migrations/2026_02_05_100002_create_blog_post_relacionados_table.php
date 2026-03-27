<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('blog_post_relacionados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_post_id')->constrained('blog_posts')->onDelete('cascade');
            $table->foreignId('relacionado_id')->constrained('blog_posts')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['blog_post_id', 'relacionado_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('blog_post_relacionados');
    }
};
