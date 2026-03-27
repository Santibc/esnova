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
        Schema::create('envios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('compra_id');
            $table->string('transportadora')->nullable();
            $table->string('numero_guia')->nullable();
            $table->enum('estado', ['preparando', 'enviado', 'en_transito', 'entregado', 'devuelto'])->default('preparando');
            $table->timestamp('fecha_envio')->nullable();
            $table->timestamp('fecha_entrega_estimada')->nullable();
            $table->timestamp('fecha_entrega')->nullable();
            $table->string('url_seguimiento')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
            
            $table->foreign('compra_id')->references('id')->on('compras')->onDelete('cascade');
            $table->index(['compra_id', 'estado']);
            $table->index('numero_guia');
        });
    }

    public function down()
    {
        Schema::dropIfExists('envios');
    }
};
