<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pais;

class PaisSeeder extends Seeder
{
    public function run()
    {
        // Si no existe, crea el registro de Colombia
        Pais::firstOrCreate([
            'nombre' => 'Colombia',
        ]);
    }
}
