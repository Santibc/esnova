<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
                // Reset cached roles and permissions
                app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

                // Crea permisos (opcional, si los necesitas ya)
               /*   Permission::create(['name' => 'Mensaje inicio']); */

        
                // Crea roles
             /*    $adminRole = Role::create(['name' => 'admin']); */
/*                 $editorRole = Role::create(['name' => 'editor']);
                $userRole = Role::create(['name' => 'user']); // Rol por defecto para usuarios registrados */
        
                // Asigna permisos a los roles (ejemplo)
                $adminRole = Role::where('id', 1)->first();
                $adminRole->givePermissionTo(['Mensaje inicio']);
                // $editorRole->givePermissionTo(['edit articles', 'publish articles']);
    }
}
