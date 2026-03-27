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
                Schema::create('actualizaciones_precios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade'); // Usuario que hizo la actualización
            $table->string('nombre_archivo'); // Nombre del archivo Excel
            $table->string('ruta_archivo'); // Ruta del archivo
            $table->integer('total_filas'); // Total de filas procesadas
            $table->integer('actualizaciones_exitosas'); // Actualizaciones exitosas
            $table->integer('actualizaciones_fallidas'); // Actualizaciones fallidas
            $table->json('errores')->nullable(); // Errores encontrados
            $table->timestamps();
            
            $table->index(['usuario_id', 'created_at']);
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
