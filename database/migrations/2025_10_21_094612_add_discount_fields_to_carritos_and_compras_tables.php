<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('carritos', function (Blueprint $table) {
            $table->decimal('descuento_total', 10, 2)->default(0)->after('subtotal');
            $table->json('descuentos_aplicados')->nullable()->after('descuento_total');
            $table->string('codigo_descuento')->nullable()->after('descuentos_aplicados');
        });

        Schema::table('compras', function (Blueprint $table) {
            $table->decimal('descuento_total', 10, 2)->default(0)->after('subtotal');
            $table->json('descuentos_aplicados')->nullable()->after('descuento_total');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carritos', function (Blueprint $table) {
            $table->dropColumn(['descuento_total', 'descuentos_aplicados', 'codigo_descuento']);
        });

        Schema::table('compras', function (Blueprint $table) {
            $table->dropColumn(['descuento_total', 'descuentos_aplicados']);
        });
    }
};
