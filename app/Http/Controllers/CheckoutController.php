<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\services\OrderServices;
use App\services\ProductServices;
use App\services\AgenciesServices;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    private $orderServices;
    private $productServices;
    private $agenciesServices;
    public function __construct(
        OrderServices $orderServices,
        ProductServices $productServices,
        AgenciesServices $agenciesServices
    ) {
        $this->orderServices = $orderServices;
        $this->productServices = $productServices;
        $this->agenciesServices = $agenciesServices;
    }

    public function index($id, Request $request)
    {
        try {
            $product = $this->productServices->findId($id);
            $agencies = $this->agenciesServices->all();
            if (!$product) {
                return response()->json(['message' => 'Order not found'], 404);
            }
            return view(
                'checkout.index',
                compact('product', 'request', 'agencies')
            );
        } catch (\Exception $e) {
            Log::error('Error in CheckoutController index: ' . $e->getMessage());
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }

    public function store(Request $request)
    {
        $request->merge(['user_id' => Auth::user()->id]);
        //  return $request;
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            //  'quantity' => 'required|min:1|exists:products,id',
            'payment_method_id' => 'required|integer|exists:payment_methods,id',
            'shipping_address' => 'required|string|max:255',

            'phone_number' => 'required|string|max:255',
            'reference_number' => 'required|string|max:255',
        ]);
        try {
            $response = $this->orderServices->create($request);
            $responseParse = json_decode($response->getContent(), true);
           
            if (isset($responseParse['error'])) {
                return view('errors.500');
            }
            return redirect()->route('profile.index')->with('success', 'Orden creada exitosamente');
        } catch (\Exception $e) {
            Log::error('Error in CheckoutController store: ' . $e->getMessage());
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }
}
