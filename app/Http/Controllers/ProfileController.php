<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\services\OrderServices;
use App\services\UserServices;
use Illuminate\Support\Facades\Auth;
use App\services\FileService;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    private $orderServices;
    private $userServices;
    private $fileServices;
    public function __construct(OrderServices $orderServices, UserServices $userServices)
    {
        $this->orderServices = $orderServices;
        $this->userServices = $userServices;
    }
    public function index()
    {
        try {
            $user = Auth::user();
            $orders = $user->orders()
                ->with(['items.product'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
            $profile = $this->userServices->getProfile($user);
            
            return view('profile.index', compact('user', 'orders', 'profile'));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }


    public function update(Request $request)
    {
        try {

            $data = array();
            $data = $request->only('phone', 'country', 'city', 'address', 'dni', 'user_id');

            if ($request->hasFile('avatar')) {
                $data['avatar'] =  $request->file('avatar');
            }
              $this->userServices->updateProfile(Auth::user(), $data);
            return  back()->with('success', 'Perfil actualizado correctamente');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }
}
