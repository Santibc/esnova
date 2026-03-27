<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ConfiguracionPasarela;
class WompiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ConfiguracionPasarela::create([
            'pasarela' => 'wompi',
            'public_key' => 'pub_test_a5fqr2xVp34oxQGj3Xyw7IqbIwp0Q8Rt',
            'private_key' => 'prv_test_AaNjzzgmj7K6VF2gT9a3LjUAeBna9g8a', // Se encriptará automáticamente
            'event_key' => 'test_events_gRBn4P6AnOVU1bOxZ4fTADsntyabovHD', // Se encriptará automáticamente
            'webhook_url' => 'http://127.0.0.1:8000/webhooks/wompi',
            'modo_prueba' => true, // Cambiar a false para producción
            'configuracion_adicional' => [
                'integrity_key' => 'test_integrity_hIQxJ0risUui0GxYAQ2DwGsKUOQ7tIM7'
            ],
            'activo' => true
        ]);
    }
}
