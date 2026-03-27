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
        Schema::create('transacciones_pago', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('compra_id');
            $table->string('pasarela')->default('wompi');
            $table->string('referencia_transaccion')->unique();
            $table->string('id_transaccion_pasarela')->nullable();
            $table->decimal('monto', 12, 2);
            $table->string('moneda', 3)->default('COP');
            $table->enum('estado', ['pendiente', 'procesando', 'aprobada', 'rechazada', 'error', 'reembolsada'])->default('pendiente');
            $table->string('metodo_pago')->nullable();
            $table->json('respuesta_pasarela')->nullable();
            $table->string('codigo_autorizacion')->nullable();
            $table->timestamp('fecha_procesamiento')->nullable();
            $table->text('mensaje_error')->nullable();
            $table->timestamps();
            
            $table->foreign('compra_id')->references('id')->on('compras')->onDelete('cascade');
            $table->index(['referencia_transaccion', 'estado']);
            $table->index(['compra_id', 'estado']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('transacciones_pago');
    }
};
