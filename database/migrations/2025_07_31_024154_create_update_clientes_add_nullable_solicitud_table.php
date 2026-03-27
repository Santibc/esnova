<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    public function up()
    {
        DB::statement("
            ALTER TABLE `solicitudes_cotizacion`
            MODIFY COLUMN `enlace_acceso_id` BIGINT UNSIGNED NULL
        ");
    }

    public function down()
    {
        DB::statement("
            ALTER TABLE `solicitudes_cotizacion`
            MODIFY COLUMN `enlace_acceso_id` BIGINT UNSIGNED NOT NULL
        ");
    }
};
