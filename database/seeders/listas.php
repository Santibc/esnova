<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class listas extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
                DB::table('listas_precios')->insert([
            ['nombre' => 'Export 1', 'codigo' => 'export1', 'descripcion' => 'Lista de precios exportación 1', 'orden' => 1],
            ['nombre' => 'Export 2', 'codigo' => 'export2', 'descripcion' => 'Lista de precios exportación 2', 'orden' => 2],
            ['nombre' => 'Local 1', 'codigo' => 'local1', 'descripcion' => 'Lista de precios local 1', 'orden' => 3],
            ['nombre' => 'Local 2', 'codigo' => 'local2', 'descripcion' => 'Lista de precios local 2', 'orden' => 4],
            ['nombre' => 'Local 3', 'codigo' => 'local3', 'descripcion' => 'Lista de precios local 3', 'orden' => 5],
            ['nombre' => 'Local 4', 'codigo' => 'local4', 'descripcion' => 'Lista de precios local 4', 'orden' => 6],
        ]);
    }
}
