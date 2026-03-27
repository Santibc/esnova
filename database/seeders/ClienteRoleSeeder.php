<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class ClienteRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear rol cliente si no existe
        if (!Role::where('name', 'cliente')->exists()) {
            Role::create(['name' => 'cliente', 'guard_name' => 'web']);
            $this->command->info('Rol "cliente" creado exitosamente.');
        } else {
            $this->command->info('El rol "cliente" ya existe.');
        }
    }
}
