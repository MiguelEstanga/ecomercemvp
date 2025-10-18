<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\services\OrderServices;
use Illuminate\Support\Facades\Auth;


class ProfileController extends Controller
{
    private $orderServices;
    public function __construct(OrderServices $orderServices)
    {
        $this->orderServices = $orderServices;
    }
    public function index()
    {
        $user = Auth::user();
          $orders = $user->orders()
            ->with(['items.product'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('profile.index', compact('user', 'orders'));
    }
}
