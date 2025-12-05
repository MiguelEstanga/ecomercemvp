<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\services\AuthServices;
use \Exception; // Para capturar errores genéricos
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function loginView()
    {
        return view('auth.index');
    }

    public function registerView()
    {
        return view('auth.create');
    }

    public function register(Request $request)
    { 

        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'password_confirmation' => 'required|same:password',
            ]);


            $user =  $this->authService->register($request->all());
            Auth::attempt(['email' => $user ->email, 'password' => Hash::make($request->password)]);
            
             return response()->redirectTo('/'); 
        } catch (\Exception $e) {
            Log::error('Error al crear el usuario: ' . $e->getMessage());
            return redirect()->back()->with('message', $e->getMessage());
        }
    }
}
