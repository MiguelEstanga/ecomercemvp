<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\services\ProductServices;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    private $productServices;
    public function __construct(ProductServices $productServices)
    {
        $this->productServices = $productServices;
    }

    public function show($id)
    {
        try {
            $product = $this->productServices->findId($id);
            return view('product.show', ['product' => $product]);
        } catch (\Exception $e) {
            Log::error('Error al obtener el producto: ' . $e->getMessage());
        }
    }
}
