<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use  App\services\ProductServices;
use App\Models\Product;
use App\Models\User;
use App\Models\Orders;
class AdminController extends Controller
{
    public $productService;
    public function __construct(
        ProductServices $productService
    ) {
        $this->productService = $productService;
    }
    public function main()
    {
        $productsActives = Product::where('is_active', true)->count();
         $ventas = Orders::where('status', 'completed')->sum('total_amount');
         
        return view('admin.main.index', [
            'products' => Product::count(),
            'users' => User::count(),
            'ordenes' => Orders::count(),
            'productsActives' => $productsActives,
            'ventas' => $ventas,
        ]);
    }

    public function productos()
    {
        try {
            $products = $this->productService->getAllProducts();
            return view('admin.products.index', compact('products'));
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    public function users()
    {
        return view('admin.users.index');
    }

    public function agencias()
    {
        return view('admin.pickup.index');
    }

    public function contactos()
    {
        return view('admin.contacto.index');
    }

    
}
