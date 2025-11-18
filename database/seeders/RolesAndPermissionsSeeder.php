<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar; // Importar para limpiar caché

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Limpiar caché: Asegura que los permisos se registren correctamente
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // --- 2. CREAR PERMISOS ---
        // (Puedes definirlos como un array para mayor facilidad)
        $permissions = [
            'crear usuarios',
            'editar usuarios',
            'eliminar usuarios',
            'ver reportes',
            'gestionar productos',
            'ver catalogo',
            'realizar pedidos',
            'ver pedidos propios',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
        
        // --- 3. CREAR ROLES ---
        $superAdminRole = Role::firstOrCreate(['name' => 'superadmin']);
        $adminRole      = Role::firstOrCreate(['name' => 'administrador']);
        $clientRole     = Role::firstOrCreate(['name' => 'cliente']);


        // --- 4. ASIGNAR PERMISOS A ROLES ---

        // Rol SuperAdmin: Tiene TODOS los permisos
        $superAdminRole->givePermissionTo(Permission::all());

        // Rol Administrador: Tiene permisos de gestión y vista, pero no super-privilegios (ej. eliminar superadmin)
        $adminRole->givePermissionTo([
            'crear usuarios',
            'editar usuarios',
            'ver reportes',
            'gestionar productos',
            'ver catalogo',
        ]);

        // Rol Cliente: Solo puede ver cosas y realizar pedidos propios
        $clientRole->givePermissionTo([
            'ver catalogo',
            'realizar pedidos',
            'ver pedidos propios',
        ]);
    }
}