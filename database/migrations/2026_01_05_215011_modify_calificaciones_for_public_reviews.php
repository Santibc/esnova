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
        // Usar SQL raw para modificar las columnas (evita necesidad de doctrine/dbal)
        DB::statement('ALTER TABLE calificaciones_productos MODIFY user_id BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE calificaciones_productos MODIFY compra_id BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE calificaciones_productos MODIFY item_compra_id BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE calificaciones_productos MODIFY aprobada TINYINT(1) NOT NULL DEFAULT 0');

        // Agregar campo para nombre del visitante
        Schema::table('calificaciones_productos', function (Blueprint $table) {
            $table->string('nombre_visitante', 100)->nullable()->after('item_compra_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Eliminar nuevo campo
        Schema::table('calificaciones_productos', function (Blueprint $table) {
            $table->dropColumn('nombre_visitante');
        });

        // Revertir cambios con SQL raw
        DB::statement('ALTER TABLE calificaciones_productos MODIFY user_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE calificaciones_productos MODIFY compra_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE calificaciones_productos MODIFY item_compra_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE calificaciones_productos MODIFY aprobada TINYINT(1) NOT NULL DEFAULT 1');
    }
};
