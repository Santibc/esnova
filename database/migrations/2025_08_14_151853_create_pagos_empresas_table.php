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
        Schema::create('pagos_empresas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empresa_id');
            $table->string('periodo');
            $table->decimal('total_ventas', 12, 2);
            $table->decimal('total_comisiones', 12, 2);
            $table->decimal('total_a_pagar', 12, 2);
            $table->enum('estado', ['pendiente', 'pagado', 'cancelado'])->default('pendiente');
            $table->date('fecha_pago')->nullable();
            $table->string('metodo_pago')->nullable();
            $table->string('referencia_pago')->nullable();
            $table->string('comprobante_pago')->nullable();
            $table->json('detalle_comisiones')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
            
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            $table->index(['empresa_id', 'estado', 'periodo']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('pagos_empresas');
    }
};
