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
                Schema::create('solicitudes_cotizacion', function (Blueprint $table) {
            $table->id();
            $table->string('numero_solicitud')->unique(); // Número de solicitud
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->foreignId('enlace_acceso_id')->constrained('enlaces_acceso')->onDelete('cascade');
            $table->enum('estado', ['pendiente', 'aplicada'])->default('pendiente'); // Estado
            $table->decimal('monto_total', 12, 2)->default(0);
            $table->text('notas_cliente')->nullable(); // Notas del cliente
            $table->text('observaciones_admin')->nullable(); // Observaciones del admin
            $table->datetime('aplicada_en')->nullable();
            $table->foreignId('aplicada_por')->nullable()->constrained('users');
            $table->timestamps();
            
            $table->index(['estado', 'created_at']);
            $table->index(['cliente_id', 'estado']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
