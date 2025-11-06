<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\services\ProductServices;
use Illuminate\Support\Facades\Log;
use App\services\CommentServices;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    private $productServices;
    private $commentService;
    public function __construct(ProductServices $productServices, CommentServices $commentService)
    {
        $this->productServices = $productServices;
        $this->commentService = $commentService;
    }

    public function show($id)
    {
        try {
            $product = $this->productServices->findId($id);
            $comment = $this->commentService->findAll($id);
            
            return view('product.show', 
            [
                'product' => $product,  
                'product_id' => $id,
                'comment' => $comment
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener el producto: ' . $e->getMessage());
        }
    }

  

    // Asume que tienes el CommentService inyectado en el constructor
    // ...

    public function commentProduct(Request $request, $product_id)
    {
        // 1. Validar la solicitud
        $request->validate([
            'content' => "required|min:3|string",
        ]);

        // 2. Preparar los metadatos para el servicio (usamos Auth::id() por brevedad)
        $metadata = [
            'user_id' => Auth::id(),
            'content' => $request->content,
            'product_id' => $product_id
        ];

        try {
            // 3. Llamar al servicio para crear el comentario
            $response = $this->commentService->create($metadata);

            // CORRECCIÓN: Si el servicio devuelve un array, NO uses json_encode().
            // Accede a la clave directamente:
            if (isset($response['success']) && $response['success'] === true) {
                // Éxito: Redirigir hacia atrás con mensaje de éxito.
                return back()->with('success', 'Tu comentario ha sido publicado con éxito.');
            }

            // Si el servicio devolvió una respuesta de error (por ejemplo, ['success' => false, 'error' => '...'])
            $errorMessage = $response['error'] ?? 'El comentario no pudo ser guardado.';
            return back()->with('error', $errorMessage);
        } catch (\Exception $e) {
            // 4. Manejo de excepciones inesperadas (ej. fallo de conexión a DB)

            // Registrar el error para el equipo de desarrollo
            Log::error('Error al crear un comentario (Producto ID: ' . $product_id . '): ' . $e->getMessage());

            // Devolver una respuesta de error al usuario
            return back()->with('error', 'Ocurrió un error inesperado al intentar publicar tu comentario. Por favor, inténtalo de nuevo.');
        }
    }
}
