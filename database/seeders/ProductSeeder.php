<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product ; // Asegúrate de que el modelo Product existe
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'CYTOTEC 200mcg tableta de 12 miligramos',
                'price' => 45,
                'stock' => 50,
                'description' => 'tableta de 12 miligramos CYTOTEC 200mcg',
                'category_id' => 1,
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

        try{
            foreach ($products as $productData) {
            Product::create(array_merge($productData, [
                'SKU' => Str::slug($productData['name']),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
        }catch (\Exception $e){
            Log::error($e->getMessage());
        }
    }
}
