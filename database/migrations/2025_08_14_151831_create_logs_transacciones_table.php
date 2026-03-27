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
        Schema::create('logs_transacciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaccion_pago_id');
            $table->string('evento');
            $table->json('datos_evento')->nullable();
            $table->string('ip_origen')->nullable();
            $table->timestamps();
            
            $table->foreign('transaccion_pago_id')->references('id')->on('transacciones_pago')->onDelete('cascade');
            $table->index(['transaccion_pago_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('logs_transacciones');
    }
};
