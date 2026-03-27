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
        // Tabla principal de stock
        Schema::create('stock_productos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('producto_id');
            $table->unsignedBigInteger('variante_producto_id')->nullable();
            $table->integer('cantidad_disponible')->default(0);
            $table->integer('cantidad_reservada')->default(0); // Para pedidos pendientes
            $table->integer('stock_minimo')->default(0);
            $table->integer('stock_maximo')->nullable();
            $table->string('ubicacion')->nullable(); // Ubicación en bodega
            $table->text('notas')->nullable();
            $table->boolean('alerta_stock_bajo')->default(true);
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('producto_id')->references('id')->on('productos')->onDelete('cascade');
            $table->foreign('variante_producto_id')->references('id')->on('variantes_productos')->onDelete('cascade');
            
            // Índices
            $table->unique(['producto_id', 'variante_producto_id']);
            $table->index(['producto_id', 'cantidad_disponible']);
        });

        // Tabla de movimientos de stock (trazabilidad)
        Schema::create('movimientos_stock', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('producto_id');
            $table->unsignedBigInteger('variante_producto_id')->nullable();
            $table->enum('tipo_movimiento', ['entrada', 'salida', 'ajuste', 'reserva', 'liberacion']);
            $table->integer('cantidad');
            $table->integer('stock_anterior');
            $table->integer('stock_nuevo');
            $table->string('referencia_documento')->nullable(); // Número de factura, orden, etc.
            $table->enum('origen', ['compra', 'venta', 'devolucion', 'ajuste_inventario', 'cotizacion', 'otro'])->default('otro');
            $table->text('motivo')->nullable();
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('solicitud_cotizacion_id')->nullable();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('producto_id')->references('id')->on('productos')->onDelete('cascade');
            $table->foreign('variante_producto_id')->references('id')->on('variantes_productos')->onDelete('cascade');
            $table->foreign('usuario_id')->references('id')->on('users');
            $table->foreign('solicitud_cotizacion_id')->references('id')->on('solicitudes_cotizacion')->onDelete('set null');
            
            // Índices
            $table->index(['producto_id', 'created_at']);
            $table->index(['tipo_movimiento', 'created_at']);
        });

        // Agregar campos de control de stock a la tabla productos
        Schema::table('productos', function (Blueprint $table) {
            $table->boolean('controlar_stock')->default(true)->after('tiene_variantes');
            $table->boolean('permitir_venta_sin_stock')->default(false)->after('controlar_stock');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn(['controlar_stock', 'permitir_venta_sin_stock']);
        });
        
        Schema::dropIfExists('movimientos_stock');
        Schema::dropIfExists('stock_productos');
    }
};
