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
        Schema::table('clientes', function (Blueprint $table) {
            // Eliminamos los campos antiguos
            $table->dropColumn(['pais','ciudad']);

            // Agregamos las claves foráneas
            $table->foreignId('pais_id')
                  ->after('telefono')
                  ->constrained('paises')
                  ->onDelete('cascade')
                  ->default(1);   // 1 = Colombia

            $table->foreignId('ciudad_id')
                  ->after('pais_id')
                  ->constrained('ciudades')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('update_clientes_add_pais_ciudad');
    }
};
