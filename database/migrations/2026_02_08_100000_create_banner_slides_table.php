<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banner_slides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade');
            $table->string('titulo')->nullable();
            $table->string('subtitulo', 500)->nullable();
            $table->string('imagen')->nullable();
            $table->string('btn1_texto')->nullable();
            $table->string('btn1_link')->nullable();
            $table->string('btn2_texto')->nullable();
            $table->string('btn2_link')->nullable();
            $table->integer('orden')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->index(['empresa_id', 'activo', 'orden']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banner_slides');
    }
};
