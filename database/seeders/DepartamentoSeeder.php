<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pais;
use App\Models\Departamento;

class DepartamentoSeeder extends Seeder
{
    public function run()
    {
        // Obtener el JSON remoto
        $json = json_decode(
            file_get_contents(
                'https://raw.githubusercontent.com/marcovega/colombia-json/master/colombia.json'
            ),
            true
        );

        $pais = Pais::where('nombre', 'Colombia')->firstOrFail();

        foreach ($json as $item) {
            Departamento::firstOrCreate([
                'pais_id' => $pais->id,
                'nombre'  => $item['departamento'],
            ]);
        }
    }
}
