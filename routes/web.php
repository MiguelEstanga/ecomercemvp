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
Route::get('/',  [MainController::class, 'index'])->name('home');
Route::get('/find-or-all' , [MainController::class, 'getProducts'])->name('product.find-or-all');

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
   Route::get('/productos' , [AdminController::class, 'productos'])->name('admin.productos');
   Route::post('/productos' , [ProductController::class, 'create'])->name('admin.productos.create');


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

Route::get('/debug-seed-fresh', function () {

    // 1. Verificar si la aplicación está en producción
    // if (app()->environment('production')) {
    //     return response()->json(['error' => 'Not allowed in production environment.'], 403);
    // }
    
    $output = '';

    try {
        // 2. Ejecutar Migraciones: Fresh (borra todas las tablas y las recrea)
        $output .= "Running migrate:fresh...\n";
        Artisan::call('migrate:fresh', [
            '--force' => true, // Necesario para entornos interactivos/producción (aunque ya filtramos)
            '--path' => 'database/migrations', // Opcional: Especificar rutas si usas múltiples carpetas
        ]);
        $output .= Artisan::output();
        
        // 3. Ejecutar Seeders
        $output .= "\nRunning db:seed...\n";
        Artisan::call('db:seed', ['--force' => true]);
        $output .= Artisan::output();

        // 4. Respuesta de éxito
        return response()->json([
            'status' => 'success',
            'message' => 'Database reset (migrate:fresh) and seeded successfully.',
            'output' => $output,
        ]); 

    } catch (\Exception $e) {
         
        return response()->json([
            'status' => 'error',
            'message' => 'An error occurred during migration or seeding.',
            'details' => $e->getMessage(),
            'output' => $output, // Mostrar output hasta el error
        ], 500);
    }
})->name('debug.seed.fresh');