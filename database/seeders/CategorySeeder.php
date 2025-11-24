<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Medicamento',
                'description' => 'Medicamentos',
            ],
            [
                'name' => 'Electrónica',
                'description' => 'Productos electrónicos',
            ],
            [
                'name' => 'Eletrónica',
                'description' => 'productos electrónicos',
            ],
            [
                'name' => 'Telefonía',
                'description' => 'telefonía',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
        
    }
}
