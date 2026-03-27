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
        Schema::create('planes_membresia', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('slug')->unique();
            $table->decimal('precio', 10, 2);
            $table->integer('limite_productos');
            $table->integer('limite_transacciones')->nullable();
            $table->decimal('porcentaje_comision', 5, 2);
            $table->decimal('comision_fija', 10, 2);
            $table->text('descripcion')->nullable();
            $table->json('caracteristicas')->nullable();
            $table->boolean('activo')->default(true);
            $table->integer('orden')->default(0);
            $table->timestamps();
        });

        // Insertar planes predefinidos
        DB::table('planes_membresia')->insert([
            [
                'nombre' => 'Plan Fundador',
                'slug' => 'plan-fundador',
                'precio' => 0,
                'limite_productos' => 10,
                'limite_transacciones' => 50,
                'porcentaje_comision' => 6.09,
                'comision_fija' => 900,
                'descripcion' => 'Plan gratuito para empezar',
                'caracteristicas' => json_encode([
                    '10 productos en tu catálogo',
                    'Subdominio propio (tumarca.Esnova.com.co)',
                    'Pasarela de Pagos integrada y segura'
                ]),
                'orden' => 1
            ],
            [
                'nombre' => 'Emprendedor',
                'slug' => 'emprendedor',
                'precio' => 85000,
                'limite_productos' => 20,
                'limite_transacciones' => 50,
                'porcentaje_comision' => 5.09,
                'comision_fija' => 900,
                'descripcion' => 'Para emprendedores en crecimiento',
                'caracteristicas' => json_encode([
                    '20 productos en tu tienda',
                    'Puntos Colombia para fidelización',
                    'Sin marca de agua de Esnova',
                    'Logística prioritaria AM',
                    'Programa Embajadores de marca'
                ]),
                'orden' => 2
            ],
            [
                'nombre' => 'Emprendedor PRO',
                'slug' => 'emprendedor-pro',
                'precio' => 110000,
                'limite_productos' => 50,
                'limite_transacciones' => 60,
                'porcentaje_comision' => 5.09,
                'comision_fija' => 800,
                'descripcion' => 'Máximo poder para tu negocio',
                'caracteristicas' => json_encode([
                    '50 productos en tu tienda',
                    'Todo lo del plan Emprendedor +',
                    'Prioridad AM y PM en entregas',
                    'IA para Creativos - Genera piezas para Instagram y Facebook',
                    'IA para Estrategia - Planes de marketing de 15 días'
                ]),
                'orden' => 3
            ],
            [
                'nombre' => 'Crecimiento',
                'slug' => 'crecimiento',
                'precio' => 500000,
                'limite_productos' => 200,
                'limite_transacciones' => 100,
                'porcentaje_comision' => 4.09,
                'comision_fija' => 700,
                'descripcion' => 'Para marcas establecidas',
                'caracteristicas' => json_encode([
                    '200 productos en tu tienda',
                    'Todo lo del plan PRO +',
                    'Embajador de Marca en marketing',
                    'Descuento en eventos presenciales',
                    '1 Sesión mensual con profesional',
                    'Opción Pasaporte a Canadá'
                ]),
                'orden' => 4
            ]
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('planes_membresia');
    }
};
