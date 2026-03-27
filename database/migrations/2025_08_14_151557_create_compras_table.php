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
        Schema::create('compras', function (Blueprint $table) {
            $table->id();
            $table->string('numero_compra')->unique();
            $table->unsignedBigInteger('empresa_id');
            $table->string('nombre_cliente');
            $table->string('email_cliente');
            $table->string('telefono_cliente');
            $table->string('direccion_envio')->nullable();
            $table->unsignedBigInteger('ciudad_id')->nullable();
            $table->decimal('subtotal', 12, 2);
            $table->decimal('impuestos', 12, 2)->default(0);
            $table->decimal('costo_envio', 10, 2)->default(0);
            $table->decimal('total', 12, 2);
            $table->enum('estado', ['pendiente', 'procesando', 'pagada', 'enviada', 'entregada', 'cancelada', 'reembolsada'])->default('pendiente');
            $table->text('notas')->nullable();
            $table->timestamps();
            
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            $table->foreign('ciudad_id')->references('id')->on('ciudades')->onDelete('set null');
            $table->index(['empresa_id', 'estado', 'created_at']);
            $table->index('numero_compra');
        });
    }

    public function down()
    {
        Schema::dropIfExists('compras');
    }
};
