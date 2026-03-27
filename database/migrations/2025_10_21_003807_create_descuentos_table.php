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
        Schema::create('descuentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->onDelete('cascade');

            // Información básica
            $table->string('codigo')->unique(); // Código único del descuento
            $table->string('nombre');
            $table->text('descripcion')->nullable();

            // Tipo de descuento: 'porcentaje', 'monto_fijo', 'envio_gratis', 'producto_gratis'
            $table->enum('tipo', ['porcentaje', 'monto_fijo', 'envio_gratis', 'producto_gratis', '2x1', '3x2'])->default('porcentaje');

            // Valor del descuento
            $table->decimal('valor', 10, 2)->default(0); // Porcentaje o monto fijo
            $table->decimal('descuento_maximo', 10, 2)->nullable(); // Máximo descuento para porcentajes

            // Aplicación del descuento
            $table->enum('aplica_a', ['orden', 'producto', 'categoria', 'carrito'])->default('orden');
            $table->json('productos_aplicables')->nullable(); // IDs de productos específicos
            $table->json('categorias_aplicables')->nullable(); // IDs de categorías específicas

            // Condiciones
            $table->decimal('monto_minimo_compra', 10, 2)->nullable(); // Monto mínimo para aplicar
            $table->integer('cantidad_minima_productos')->nullable(); // Cantidad mínima de productos
            $table->boolean('solo_primera_compra')->default(false); // Solo para nuevos clientes

            // Restricciones de uso
            $table->integer('limite_usos_total')->nullable(); // Límite total de usos
            $table->integer('usos_actuales')->default(0); // Contador de usos
            $table->integer('limite_usos_por_cliente')->nullable(); // Límite por cliente

            // Vigencia
            $table->dateTime('fecha_inicio')->nullable();
            $table->dateTime('fecha_fin')->nullable();

            // Estado
            $table->boolean('activo')->default(true);
            $table->boolean('es_acumulable')->default(false); // Puede combinarse con otros descuentos
            $table->integer('prioridad')->default(0); // Orden de aplicación (mayor = primero)

            $table->timestamps();

            // Índices
            $table->index(['empresa_id', 'activo']);
            $table->index('codigo');
            $table->index(['fecha_inicio', 'fecha_fin']);
        });

        // Tabla para registrar el uso de descuentos por compra
        Schema::create('descuentos_aplicados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('descuento_id')->constrained('descuentos')->onDelete('cascade');
            $table->foreignId('compra_id')->constrained('compras')->onDelete('cascade');
            $table->string('email_cliente')->nullable();
            $table->decimal('monto_descuento', 10, 2); // Monto descontado
            $table->timestamps();

            $table->index(['descuento_id', 'compra_id']);
            $table->index(['descuento_id', 'email_cliente']);
        });

        // Tabla para relacionar productos con descuentos específicos
        Schema::create('descuento_producto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('descuento_id')->constrained('descuentos')->onDelete('cascade');
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['descuento_id', 'producto_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('descuento_producto');
        Schema::dropIfExists('descuentos_aplicados');
        Schema::dropIfExists('descuentos');
    }
};
