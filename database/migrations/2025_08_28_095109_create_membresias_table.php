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
        Schema::create('membresias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('plan_membresia_id');
            $table->enum('estado', ['activa', 'cancelada', 'vencida', 'pendiente'])->default('pendiente');
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->decimal('precio_pagado', 10, 2);
            $table->unsignedBigInteger('transaccion_pago_id')->nullable();
            $table->timestamps();
            
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            $table->foreign('plan_membresia_id')->references('id')->on('planes_membresia');
            $table->foreign('transaccion_pago_id')->references('id')->on('transacciones_pago');
            
            $table->index(['empresa_id', 'estado']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('membresias');
    }
};
