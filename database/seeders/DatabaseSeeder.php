<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. EJECUTAR LOS ROLES Y PERMISOS PRIMERO
        // Los roles deben existir en la BD antes de que puedas asignarlos a un usuario.
        $this->call([
            RolesAndPermissionsSeeder::class, // <-- ¡MOVEMOS ESTE SEEDER ARRIBA!
        ]);

        // --- CREACIÓN Y ASIGNACIÓN DE USUARIOS CON ROLES ---

        // 1. Usuario SuperAdmin (Ejemplo: "administrador" o "superadmin")
        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@tienda.com',
            'password' => Hash::make('password'),
        ]);
        // ASIGNACIÓN DE ROL:
        $adminUser->assignRole('administrador'); // O 'superadmin', según cómo lo creaste en el seeder

        // 2. Usuario Cliente
        $clientUser = User::create([
            'name' => 'Cliente de Prueba',
            'email' => 'cliente@tienda.com',
            'password' => Hash::make('password'),
        ]);
        // ASIGNACIÓN DE ROL:
        $clientUser->assignRole('cliente');

        // 3. (Opcional) Usar Faker para crear 10 usuarios aleatorios
        // A estos usuarios les podemos asignar el rol 'cliente' por defecto:
        User::factory()->count(10)->create()->each(function ($user) {
             $user->assignRole('cliente');
        });

        // --- LLAMADAS A OTROS SEEDERS (Que ya no dependen de los Roles) ---
        $this->call([
            PaymentMethodSeeder::class,
            PickupAgencySeeder::class,
            ProductSeeder::class,
            OrderSeeder::class, // Debe ir al final ya que depende de todos los anteriores
        ]);
    }
}