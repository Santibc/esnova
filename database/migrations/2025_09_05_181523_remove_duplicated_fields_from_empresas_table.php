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
            // Eliminar campos duplicados que ahora vendrán de planes_membresia
            $table->dropColumn([
                'limite_productos',
                'porcentaje_comision', 
                'comision_fija',
                'cargo_fijo_comision'
            ]);
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
            // Restaurar los campos eliminados
            $table->integer('limite_productos')->default(10)->after('plan_membresia_id');
            $table->decimal('porcentaje_comision', 5, 2)->default(5.00)->after('limite_productos');
            $table->decimal('comision_fija', 8, 2)->default(0.00)->after('porcentaje_comision');
            $table->decimal('cargo_fijo_comision', 8, 2)->default(0.00)->after('comision_fija');
        });
    }
};
