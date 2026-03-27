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
    public function up(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            // Índice actual en tu dump: productos_referencia_unique
            $table->dropUnique('productos_referencia_unique');
            // Nuevo índice compuesto por empresa
            $table->unique(['empresa_id', 'referencia'], 'productos_empresa_referencia_unique');
        });
    }

    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropUnique('productos_empresa_referencia_unique');
            $table->unique('referencia', 'productos_referencia_unique');
        });
    }
};
