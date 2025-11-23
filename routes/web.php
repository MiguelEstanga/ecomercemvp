<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use Illuminate\Support\Facades\File;

Route::get('/',  [MainController::class, 'index'])->name('home');
Route::get('/find-or-all', [MainController::class, 'getProducts'])->name('product.find-or-all');

Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/login', [AuthController::class, 'loginView'])->name('login');
Route::get('/register', [AuthController::class, 'registerView'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::prefix('product')->group(function () {
  Route::get('/{id}', [ProductController::class, 'show'])->name('product.show');
  Route::post('comment/{product_id}', [ProductController::class, 'commentProduct'])->name('product.comment');
});

Route::prefix('checkout')->group(function () {
  Route::get('/{id}', [CheckoutController::class, 'index'])->name('checkout.index');
  Route::post('/', [CheckoutController::class, 'store'])->name('checkout.store');
});

Route::prefix('profile')->middleware('auth')->group(function () {
  Route::get('/', [ProfileController::class, 'index'])->name('profile.index');
  Route::post('/', [ProfileController::class, 'update'])->name('profile.update');
});

Route::prefix('order')->middleware('auth')->group(function () {
  Route::get('/{id}', [OrderController::class, 'show'])->name('order.show');
});


Route::prefix('user')->middleware('auth')->group(function () {

  Route::post('/edit/{userID}', [UserController::class, 'update'])->name('user.update');
});


Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
  Route::get('/', [AdminController::class, 'main'])->name('admin.dashboard');
  Route::get('/productos', [AdminController::class, 'productos'])->name('admin.productos');
  Route::post('/productos', [ProductController::class, 'create'])->name('admin.productos.create');


  Route::prefix('order')->group(function () {
    Route::get('/', [OrderController::class, 'index'])->name('order.admin.index');
  });


  Route::prefix('usuarios')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('user.admin.index');
  });

  Route::prefix("agencias")->group(function () {
    Route::get('/', [AdminController::class, 'agencias'])->name('agency.admin.index');
  });
});
// SOLO PARA DEBUGGING - ELIMINAR EN PRODUCCIÓN
Route::get('/debug-seed', function () {

  Artisan::call('db:seed', ['--force' => true]);
  return json_encode([
    'output' => Artisan::output(),

  ]);
  return 'Not allowed';
});

Route::get('/refresh-db', function () {
    try {
        Artisan::call('migrate:fresh', ['--force' => true]);
        Artisan::call('db:seed', ['--force' => true]);
        
        return response()->json([
            'status' => 'success',
            'message' => 'Base de datos actualizada correctamente'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
});


Route::get('/sitemap.xml', function () {
  $products = Product::where('is_active', true)->get();

  return response()->view('sitemap', [
    'products' => $products,
  ])->header('Content-Type', 'text/xml');
});


 
Route::get('/view-log', function () {
    $logFile = storage_path('logs/laravel.log');
    
    if (!File::exists($logFile)) {
        return 'Log file not found';
    }
    
    $content = File::get($logFile);
    
    return response($content)->header('Content-Type', 'text/plain');
})->name('view.log');