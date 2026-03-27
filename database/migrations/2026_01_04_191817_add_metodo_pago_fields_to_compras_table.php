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
        Schema::table('compras', function (Blueprint $table) {
            $table->enum('metodo_pago', ['wompi', 'otro'])->default('wompi')->after('estado');
            $table->text('mensaje_pago')->nullable()->after('metodo_pago');
            $table->string('archivo_pago')->nullable()->after('mensaje_pago');
            $table->text('motivo_rechazo')->nullable()->after('archivo_pago');
            $table->timestamp('fecha_revision')->nullable()->after('motivo_rechazo');
            $table->unsignedBigInteger('revisado_por')->nullable()->after('fecha_revision');

            $table->foreign('revisado_por')->references('id')->on('users')->onDelete('set null');
            $table->index(['empresa_id', 'metodo_pago', 'estado']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('compras', function (Blueprint $table) {
            $table->dropForeign(['revisado_por']);
            $table->dropIndex(['empresa_id', 'metodo_pago', 'estado']);
            $table->dropColumn([
                'metodo_pago',
                'mensaje_pago',
                'archivo_pago',
                'motivo_rechazo',
                'fecha_revision',
                'revisado_por'
            ]);
        });
    }
};
