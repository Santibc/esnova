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
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->integer('id_tabla')->nullable();
            $table->string('tabla')->default('llamadas');
            $table->text('detalle')->comment('Comentario del cambio de estado');
            $table->string('tipo_log')->default('1')->comment('1 para cambio de estado');
            $table->string('valor_viejo')->nullable();
            $table->string('valor_nuevo')->nullable();
            $table->foreignId('id_usuario')->constrained('users');
            $table->boolean('estado')->default(true);
            $table->integer('id_archivo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logs');
    }
};
