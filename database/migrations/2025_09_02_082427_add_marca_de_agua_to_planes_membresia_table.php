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
        Schema::table('planes_membresia', function (Blueprint $table) {
            $table->boolean('marca_de_agua')->default(false)->after('activo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('planes_membresia', function (Blueprint $table) {
            $table->dropColumn('marca_de_agua');
        });
    }
};
