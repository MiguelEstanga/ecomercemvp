<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\products as Product;
use App\Models\Orders;
use App\Models\PaymentMethods;
use App\Models\PinchupAgencies;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'cliente@tienda.com')->first();
        $product1 = Product::where('name', 'Taza de Café Programador')->first();
        $product2 = Product::where('name', 'Camiseta Laravel Roja')->first();
        $paymentMethod = PaymentMethods::where('name', 'Tarjeta de Crédito / Débito')->first();
        $agency = PinchupAgencies::where('name', 'Agencia Principal - Centro')->first();

        if (!$user || !$product1 ||   !$paymentMethod || !$agency) {
            // Esto asegura que si no existen los datos base, no falla
            return;
        }

        // --- Crear un Pedido de Ejemplo (Checkout Directo) ---

        $order = Orders::create([
            'user_id' => $user->id,
            'order_number' => 'ORD-' . time(),
            'total_amount' => ($product1->price * 2) + ($product2->price * 1), // 2 Tazas + 1 Camiseta
            'status' => 'completed',
            'payment_method_id' => $paymentMethod->id,
            'pickup_agency_id' => $agency->id,
            'shipping_address' => 'Dirección de Prueba del Cliente',
        ]);

        // --- Items del Pedido ---

        $order->items()->createMany([
            [
                'product_id' => $product1->id,
                'product_name' => $product1->name,
                'unit_price' => $product1->price,
                'quantity' => 2,
            ],
            [
                'product_id' => $product2->id,
                'product_name' => $product2->name,
                'unit_price' => $product2->price,
                'quantity' => 1,
            ]
        ]);

        // ¡Importante! Aquí deberías restar el stock de Product
        $product1->decrement('stock', 2);
        $product2->decrement('stock', 1);
    }
}
