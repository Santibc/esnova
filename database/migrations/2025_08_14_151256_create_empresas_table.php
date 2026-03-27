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
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('usuario_id')->unique();
            $table->string('nombre');
            $table->string('slug')->unique();
            $table->string('descripcion', 500)->nullable();
            $table->string('logo')->nullable();
            $table->string('imagen_portada')->nullable();
            $table->string('email')->nullable();
            $table->string('telefono')->nullable();
            $table->string('direccion')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('whatsapp')->nullable();
            $table->json('horario_atencion')->nullable();
            $table->boolean('activo')->default(true);
            $table->decimal('porcentaje_comision', 5, 2)->default(10.00);
            $table->timestamps();
            
            $table->foreign('usuario_id')->references('id')->on('users')->onDelete('cascade');
            $table->index(['slug', 'activo']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('empresas');
    }
};
