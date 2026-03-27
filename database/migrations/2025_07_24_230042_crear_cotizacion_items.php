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
                Schema::create('items_solicitud_cotizacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('solicitud_cotizacion_id')->constrained('solicitudes_cotizacion')->onDelete('cascade');
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            $table->foreignId('variante_producto_id')->nullable()->constrained('variantes_productos')->onDelete('cascade');
            $table->integer('cantidad');
            $table->decimal('precio_unitario', 10, 2);
            $table->decimal('precio_total', 12, 2);
            $table->string('referencia_producto'); // Guardar referencia por si el producto cambia
            $table->string('nombre_producto'); // Guardar nombre por si el producto cambia
            $table->string('info_variante')->nullable(); // Información de la variante (talla, color)
            $table->timestamps();
            
            $table->index('solicitud_cotizacion_id');
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
