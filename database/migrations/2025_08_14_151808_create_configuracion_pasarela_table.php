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
        Schema::create('configuracion_pasarela', function (Blueprint $table) {
            $table->id();
            $table->string('pasarela')->default('wompi');
            $table->string('public_key')->nullable();
            $table->string('private_key')->nullable();
            $table->string('event_key')->nullable();
            $table->string('webhook_url')->nullable();
            $table->boolean('modo_prueba')->default(true);
            $table->json('configuracion_adicional')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            $table->index('pasarela');
        });
    }

    public function down()
    {
        Schema::dropIfExists('configuracion_pasarela');
    }
};
