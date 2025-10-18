<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\services\OrderServices;
use Illuminate\Support\Facades\Auth;

 
class OrderController extends Controller
{
    public function __construct(
        private OrderServices $orderService
    ) {}

    /**
     * Mostrar detalle de una orden
     */
    public function show(int $id  )
    {
         
         $order = $this->orderService->getOrderForUser($id , Auth::user()->id);

        if (!$order) {
            abort(404, 'Orden no encontrada');
        }

        return view('order.show', compact('order'));
    }
}
