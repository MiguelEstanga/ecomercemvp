<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\services\ProductServices;
use Illuminate\Support\Facades\Log;
use App\services\CommentServices;
use Illuminate\Support\Facades\Auth;
use App\services\FileService;
use App\Models\ProductImagen;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    private $productServices;
    private $commentService;
    private $fileService;
    public function __construct(
        ProductServices $productServices,
        CommentServices $commentService,
        FileService $fileService
    ) {
        $this->productServices = $productServices;
        $this->commentService = $commentService;
        $this->fileService = $fileService;
    }

    public function show($id)
    {
        try {
            $product = $this->productServices->findId($id);
            if ($product->is_active == false) {
                return redirect()->route('home');
            }
            $comment = $this->commentService->findAll($id);

            return view(
                'product.show',
                [
                    'product' => $product,
                    'product_id' => $id,
                    'comment' => $comment
                ]
            );
        } catch (\Exception $e) {
            Log::error('Error al obtener el producto: ' . $e->getMessage());
        }
    }



    // Asume que tienes el CommentService inyectado en el constructor

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

    public function create(Request $request)
    {
        Log::info('creando archivo ');
        DB::beginTransaction();
        Log::info('preparndo transaction ');
        $path = null;
        if ($request->hasFile('imagen')) {
            $path = $this->fileService->upload($request->file('imagen'), 'product', 'public');
        }

        try {
            $imagenes = $request->file('imagen');
            $producto = $this->productServices->createProduct($request->all(), $imagenes);

            ProductImagen::create([
                'product_id' => $producto['data']->id,
                'path' => $path,
                'is_main' => true
            ]);
            DB::commit();
            return back()->with('success', 'Producto creado correctamente');
        } catch (\Exception $e) {

            Log::error('Error al crear producto: ' . $e->getMessage());
            DB::rollBack();
            if ($request->hasFile('imagen')) {
                $this->fileService->delete($path);
            }
            return response()->json(['message' => 'Error al crear producto'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            //  return $request->all();
            // Validar los datos de entrada
            // $request->validate([
            //     'name' => 'required|string|max:255',
            //     'price' => 'required|numeric|min:0',
            //     'stock' => 'required|integer|min:0',
            //     'category_id' => 'required|exists:categories,id',
            //     'SKU' => 'nullable|string|unique:products,sku,' . $id,
            //     'imagen.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5048',
            //     'imagenDelete.*' => 'nullable|integer|exists:product_imagens,id',
            //     'is_active' => 'sometimes|boolean',
            // ]);

              $producto = $this->productServices->updateProduct($id, [
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'stock' => $request->stock,
                'category_id' => $request->category_id,
                'is_active' => $request->is_active,
                'SKU' => $request->sku,

            ]);
            $oldPath = $producto->getFirstImageAttribute();
            if ($oldPath) {
                 
                $this->fileService->delete($oldPath, 'products');
                 ProductImagen::where('product_id', $producto->id)->delete();
            }
            // Subir imágenes nuevas
            if ($request->hasFile('imagen')) {
                $path = $this->fileService->upload($request->file('imagen'), 'products');
               
                ProductImagen::create([
                    'product_id' => $producto->id,
                    'path' => $path,
                    'is_primary' => true
                ]);
            }
            return back()->with('success', 'Producto actualizado correctamente');
        } catch (\Exception $e) {
            Log::error('Error al actualizar producto ID ' . $id . ': ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return 0;
            return back()->with('error', 'Ocurrió un error al actualizar el producto: ' . $e->getMessage());
        }
    }
}
