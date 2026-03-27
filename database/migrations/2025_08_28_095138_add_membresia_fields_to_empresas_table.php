<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
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
            $table->unsignedBigInteger('plan_membresia_id')->nullable()->after('porcentaje_comision');
            $table->integer('limite_productos')->default(10)->after('plan_membresia_id');
            $table->decimal('comision_fija', 10, 2)->default(900)->after('porcentaje_comision');
            
            $table->foreign('plan_membresia_id')->references('id')->on('planes_membresia');
        });
        
        // Actualizar empresas existentes con plan fundador
        DB::table('empresas')->update([
            'plan_membresia_id' => 1,
            'limite_productos' => 10,
            'porcentaje_comision' => 6.09,
            'comision_fija' => 900
        ]);
    }

    public function down()
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropForeign(['plan_membresia_id']);
            $table->dropColumn(['plan_membresia_id', 'limite_productos', 'comision_fija']);
        });
    }
};
