<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Descuento;
use App\Models\Empresa;
use Carbon\Carbon;

class DescuentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener todas las empresas
        $empresas = Empresa::all();

        foreach ($empresas as $empresa) {
            // Descuento de bienvenida (10% de descuento)
            Descuento::create([
                'empresa_id' => $empresa->id,
                'codigo' => 'BIENVENIDO10',
                'nombre' => 'Descuento de Bienvenida',
                'descripcion' => '10% de descuento en tu primera compra',
                'tipo' => 'porcentaje',
                'valor' => 10,
                'descuento_maximo' => null,
                'aplica_a' => 'orden',
                'productos_aplicables' => null,
                'categorias_aplicables' => null,
                'monto_minimo_compra' => 50000,
                'cantidad_minima_productos' => 1,
                'solo_primera_compra' => true,
                'limite_usos_total' => null,
                'usos_actuales' => 0,
                'limite_usos_por_cliente' => 1,
                'fecha_inicio' => Carbon::now(),
                'fecha_fin' => Carbon::now()->addMonths(3),
                'activo' => true,
                'es_acumulable' => false,
                'prioridad' => 10,
            ]);

            // Descuento de verano (15% con límite)
            Descuento::create([
                'empresa_id' => $empresa->id,
                'codigo' => 'VERANO15',
                'nombre' => 'Descuento de Verano',
                'descripcion' => '15% de descuento con tope de $20.000',
                'tipo' => 'porcentaje',
                'valor' => 15,
                'descuento_maximo' => 20000,
                'aplica_a' => 'orden',
                'productos_aplicables' => null,
                'categorias_aplicables' => null,
                'monto_minimo_compra' => 100000,
                'cantidad_minima_productos' => null,
                'solo_primera_compra' => false,
                'limite_usos_total' => 100,
                'usos_actuales' => 0,
                'limite_usos_por_cliente' => 3,
                'fecha_inicio' => Carbon::now(),
                'fecha_fin' => Carbon::now()->addMonth(),
                'activo' => true,
                'es_acumulable' => false,
                'prioridad' => 8,
            ]);

            // Descuento fijo para compras grandes
            Descuento::create([
                'empresa_id' => $empresa->id,
                'codigo' => 'AHORRA30K',
                'nombre' => 'Descuento $30.000',
                'descripcion' => '$30.000 de descuento en compras mayores a $200.000',
                'tipo' => 'monto_fijo',
                'valor' => 30000,
                'descuento_maximo' => null,
                'aplica_a' => 'orden',
                'productos_aplicables' => null,
                'categorias_aplicables' => null,
                'monto_minimo_compra' => 200000,
                'cantidad_minima_productos' => null,
                'solo_primera_compra' => false,
                'limite_usos_total' => null,
                'usos_actuales' => 0,
                'limite_usos_por_cliente' => null,
                'fecha_inicio' => Carbon::now(),
                'fecha_fin' => null,
                'activo' => true,
                'es_acumulable' => true,
                'prioridad' => 5,
            ]);

            // Descuento del mes (5% automático)
            Descuento::create([
                'empresa_id' => $empresa->id,
                'codigo' => null, // Sin código = automático
                'nombre' => 'Descuento del Mes',
                'descripcion' => '5% de descuento automático en todas las compras',
                'tipo' => 'porcentaje',
                'valor' => 5,
                'descuento_maximo' => 15000,
                'aplica_a' => 'orden',
                'productos_aplicables' => null,
                'categorias_aplicables' => null,
                'monto_minimo_compra' => 80000,
                'cantidad_minima_productos' => 2,
                'solo_primera_compra' => false,
                'limite_usos_total' => null,
                'usos_actuales' => 0,
                'limite_usos_por_cliente' => null,
                'fecha_inicio' => Carbon::now()->startOfMonth(),
                'fecha_fin' => Carbon::now()->endOfMonth(),
                'activo' => false, // Inactivo por defecto
                'es_acumulable' => true,
                'prioridad' => 3,
            ]);
        }
    }
}
