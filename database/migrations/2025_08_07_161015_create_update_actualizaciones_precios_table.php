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
        Schema::table('actualizaciones_precios', function (Blueprint $table) {
            // Agregar nuevas columnas si no existen
            if (!Schema::hasColumn('actualizaciones_precios', 'detalles_procesados')) {
                $table->longText('detalles_procesados')->nullable()->after('errores');
            }
            if (!Schema::hasColumn('actualizaciones_precios', 'estado')) {
                $table->enum('estado', ['procesando', 'completado', 'error'])->default('procesando')->after('usuario_id');
            }
        });
    }

    public function down()
    {
        Schema::table('actualizaciones_precios', function (Blueprint $table) {
            $table->dropColumn(['detalles_procesados', 'estado']);
        });
    }
};
