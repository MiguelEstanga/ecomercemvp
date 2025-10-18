<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\products as Product; // Asegúrate de que el modelo Product existe
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'Taza de Café Programador',
                'price' => 12.99,
                'stock' => 50,
                'description' => 'Taza de cerámica con diseño de código binario. ¡Perfecta para la cafeína de la mañana!',
            ],
            [
                'name' => 'Camiseta Laravel Roja',
                'price' => 25.50,
                'stock' => 20,
                'description' => 'Camiseta de algodón 100% con el logo de Laravel en color rojo vibrante.',
            ],
            [
                'name' => 'Sticker Pack (50 und.)',
                'price' => 5.00,
                'stock' => 100,
                'description' => 'Paquete de 50 stickers variados de temática tecnológica y desarrollo web.',
            ],
        ];

        foreach ($products as $productData) {
            Product::create(array_merge($productData, [
                'slug' => Str::slug($productData['name']),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
