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
        Schema::table('productos', function (Blueprint $table) {
            $table->string('info_envio')->nullable()->after('permitir_venta_sin_stock');
            $table->string('dias_devolucion')->nullable()->after('info_envio');
            $table->string('garantia')->nullable()->after('dias_devolucion');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn(['info_envio', 'dias_devolucion', 'garantia']);
        });
    }
};
