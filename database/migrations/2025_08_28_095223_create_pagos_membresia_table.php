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
        Schema::create('pagos_membresia', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('membresia_id');
            $table->decimal('monto', 10, 2);
            $table->string('referencia_pago');
            $table->enum('estado', ['pendiente', 'aprobado', 'rechazado', 'reembolsado'])->default('pendiente');
            $table->string('metodo_pago')->nullable();
            $table->json('respuesta_pasarela')->nullable();
            $table->timestamp('fecha_pago')->nullable();
            $table->timestamps();
            
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            $table->foreign('membresia_id')->references('id')->on('membresias')->onDelete('cascade');
            
            $table->index(['empresa_id', 'estado']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('pagos_membresia');
    }
};
