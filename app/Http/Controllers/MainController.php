<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\services\ProductServices;
use Illuminate\Support\Facades\Log;


class MainController extends Controller
{
    private $productServices;
    public function __construct(
        ProductServices $productServices
    ) {
        $this->productServices = $productServices;
    }

    public function index()
    {
        try {
            $products = $this->productServices->getAllProducts();
            return view('main.index', ['products' => $products]);
        } catch (\Exception $e) {
            Log::error('Error al obtener los productos: ' . $e->getMessage());
        }
    }
}
