<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use  App\services\ProductServices;

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
        return view('admin.main.index');
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
}
