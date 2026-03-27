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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('numero_identificacion')->unique(); // Número de Identificación
            $table->string('nombre_contacto'); // Contacto
            $table->string('email'); // Correo electrónico
            $table->string('telefono')->nullable(); // Teléfono
            $table->string('pais'); // País
            $table->string('ciudad'); // Ciudad
            $table->foreignId('vendedor_id')->constrained('users')->onDelete('cascade'); // Vendedor
            $table->foreignId('lista_precio_id')->constrained('listas_precios')->onDelete('cascade'); // Lista de Precios
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            $table->index(['vendedor_id', 'activo']);
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
