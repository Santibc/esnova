<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Departamento;
use App\Models\Ciudad;

class CiudadSeeder extends Seeder
{
    public function run()
    {
        // Mismo JSON que en DepartamentoSeeder
        $json = json_decode(
            file_get_contents(
                'https://raw.githubusercontent.com/marcovega/colombia-json/master/colombia.json'
            ),
            true
        );

        foreach ($json as $item) {
            $departamento = Departamento::where('nombre', $item['departamento'])->first();

            if (! $departamento) {
                continue;
            }

            foreach ($item['ciudades'] as $ciudadNombre) {
                Ciudad::firstOrCreate([
                    'departamento_id' => $departamento->id,
                    'nombre'          => $ciudadNombre,
                ]);
            }
        }
    }
}
