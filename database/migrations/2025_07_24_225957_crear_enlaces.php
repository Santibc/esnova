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
                Schema::create('enlaces_acceso', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->foreignId('creado_por')->constrained('users')->onDelete('cascade'); // Usuario que creó el enlace
            $table->string('token')->unique(); // Token único para el link
            $table->integer('dias_validos'); // Días válidos del enlace
            $table->boolean('mostrar_precios')->default(true); // Visibilidad de precios
            $table->datetime('expira_en'); // Fecha de expiración
            $table->boolean('activo')->default(true);
            $table->integer('visitas')->default(0); // Contador de visitas
            $table->datetime('ultimo_acceso')->nullable();
            $table->timestamps();
            
            $table->index(['token', 'activo']);
            $table->index(['expira_en', 'activo']);
            $table->index('creado_por');
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
