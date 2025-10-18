<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'name' => 'Admin User',
            'email' => 'admin@tienda.com',
            'password' => Hash::make('password'), // Recuerda cambiar esto en producción
            // Puedes añadir un campo 'role' si lo tienes: 'role' => 'admin'
        ]);

        // 2. Usuario Cliente
        User::create([
            'name' => 'Cliente de Prueba',
            'email' => 'cliente@tienda.com',
            'password' => Hash::make('password'),
        ]);

        // 3. (Opcional) Usar Faker para crear 10 usuarios aleatorios
        User::factory()->count(10)->create();

        $this->call([
            PaymentMethodSeeder::class,
            PickupAgencySeeder::class,
            ProductSeeder::class,
            OrderSeeder::class, // Debe ir al final ya que depende de todos los anteriores
        ]);
    }
}
