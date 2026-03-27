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
        Schema::table('empresas', function (Blueprint $table) {
            $table->foreignId('template_tienda_id')
                  ->nullable()
                  ->after('activo')
                  ->constrained('templates_tienda')
                  ->nullOnDelete()
                  ->comment('Template de tienda seleccionado');

            $table->index('template_tienda_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropForeign(['template_tienda_id']);
            $table->dropColumn('template_tienda_id');
        });
    }
};
