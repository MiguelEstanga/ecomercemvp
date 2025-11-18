<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\services\ProductServices;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;


class MainController extends Controller
{
    private $productServices;
    public function __construct(
        ProductServices $productServices
    ) {
        $this->productServices = $productServices;
    }

    public function getProducts(Request $request)
    {
        try {
            $buscar = $request->get('buscar');

            if ($buscar) {
                $products = Cache::remember("search_" . md5($buscar), 30, function () use ($buscar) {
                    return $this->productServices->buscarPorNombre($buscar);
                });
                $html = view('main.ajax.products', ['products' => $products])->render();
                return response()->json(['html' => $html]);
            }

            $products = $this->productServices->getAllProducts();

            // Si es una petición AJAX sin búsqueda, devolvemos también HTML
            if ($request->ajax()) {
                $html = view('main.ajax.products', compact('products'))->render();
                return response()->json(['html' => $html]);
            }
            $products = Cache::remember('all_products', 60, function () {
                return $this->productServices->getAllProducts();
            });
            // Si es carga normal (primera vez), devolvemos la vista completa
            return view('main.index', compact('products'));
        } catch (\Exception $e) {
            Log::error('Error al obtener los productos: ' . $e->getMessage());
            return response()->json(['html' => '<p>Error al cargar productos.</p>'], 500);
        }
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
