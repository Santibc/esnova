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
            $table->decimal('cargo_fijo_comision', 10, 2)
                  ->default(900.00)
                  ->after('porcentaje_comision');
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
            $table->dropColumn('cargo_fijo_comision');
        });
    }
};
