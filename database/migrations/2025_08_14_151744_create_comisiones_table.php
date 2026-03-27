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
        Schema::create('comisiones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('compra_id');
            $table->decimal('monto_venta', 12, 2);
            $table->decimal('porcentaje_comision', 5, 2);
            $table->decimal('monto_comision', 12, 2);
            $table->decimal('monto_empresa', 12, 2);
            $table->enum('estado', ['pendiente', 'procesada', 'pagada'])->default('pendiente');
            $table->date('fecha_pago')->nullable();
            $table->string('referencia_pago')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
            
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            $table->foreign('compra_id')->references('id')->on('compras')->onDelete('cascade');
            $table->index(['empresa_id', 'estado', 'created_at']);
            $table->index('compra_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('comisiones');
    }
};
