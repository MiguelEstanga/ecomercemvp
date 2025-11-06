<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\services\AuthServices;
use \Exception; // Para capturar errores genéricos
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthServices $authService)
    {
        $this->authService = $authService;
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');
            $user = $this->authService->login($credentials['email'], password: $credentials['password']);

            if ($user) {
                return response()->redirectTo('/');
            } else {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }
        } catch (Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function loginView(){
        return view('auth.index');
    }
}
