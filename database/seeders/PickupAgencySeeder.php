<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PickupAgencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pinchup_agencies')->insert([
            [
                'name' => 'Agencia Principal - Centro',
                'address' => 'Calle Falsa 123, Ciudad Capital',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Punto de Retiro - Sur',
                'address' => 'Avenida Siempre Viva 742, Sector Sur',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
