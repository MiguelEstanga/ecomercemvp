<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use App\services\FileService;
use App\Models\Product;
use App\Models\ProductImagen as ProductImagens;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductServices
{
    protected $productRepository;
    protected $fileService;

    public function __construct(
        FileService $fileService,
        ProductRepository $productRepository
    ) {
        $this->productRepository = $productRepository;
        $this->fileService = $fileService;
    }

    /**
     * Obtener todos los productos
     */
    public function getAllProducts()
    {
        return $this->productRepository->all();
    }

    /**
     * Buscar producto por ID
     */
    public function findId($id)
    {
        $product = $this->productRepository->findId($id);
        if (!$product) {
            return null;
        }
        return $product;
    }

    /**
     * Obtener productos con filtros y paginación
     */
    public function getProductsWithFilters(array $filters)
    {
        $query = Product::with(['category', 'product_imagens']);

        // Búsqueda
        if (!empty($filters['search'])) {
            $query->search($filters['search']);
        }

        // Filtro por categoría
        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        // Filtro por estado
        if (isset($filters['is_active']) && $filters['is_active'] !== '') {
            $query->where('is_active', $filters['is_active']);
        }

        // Filtro por stock
        if (!empty($filters['stock_filter'])) {
            switch ($filters['stock_filter']) {
                case 'in_stock':
                    $query->where('stock', '>=', 10);
                    break;
                case 'low_stock':
                    $query->where('stock', '>', 0)->where('stock', '<', 10);
                    break;
                case 'out_of_stock':
                    $query->where('stock', 0);
                    break;
            }
        }

        // Ordenamiento
        $sortField = $filters['sort_field'] ?? 'created_at';
        $sortDirection = $filters['sort_direction'] ?? 'desc';
        $query->orderBy($sortField, $sortDirection);

        // Paginación
        $perPage = $filters['per_page'] ?? 10;
        return $query->paginate($perPage);
    }

    /**
     * Obtener estadísticas de productos
     */
    public function getStatistics(): array
    {
        return [
            'total' => Product::count(),
            'active' => Product::where('is_active', true)->count(),
            'out_of_stock' => Product::where('stock', 0)->count(),
            'low_stock' => Product::where('stock', '>', 0)->where('stock', '<', 10)->count(),
        ];
    }

    /**
     * Descontar stock de un producto
     */
    public function discount_stok($product_id, $quantity)
    {
        return $this->productRepository->discount_stok($product_id, $quantity);
    }

    /**
     * Buscar productos por nombre
     */
    public function buscarPorNombre($nombre)
    {
        return $this->productRepository->buscarPorNombre($nombre);
    }

    /**
     * Crear un nuevo producto con imágenes
     */
    public function createProduct(array $data, array $images = []): array
    {
        DB::beginTransaction();

        try {
            // Crear producto
            $product = $this->productRepository->create([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'price' => $data['price'],
                'stock' => $data['stock'],
                'category_id' => $data['category_id'],
                'is_active' => $data['is_active'] ?? true,
                'sku' => $data['sku'] ?? null,
            ]);

            // Subir imágenes si existen
            if (!empty($images)) {
                foreach ($images as $index => $image) {
                    $path = $this->fileService->upload($image, 'products');

                    ProductImagens::create([
                        'product_id' => $product->id,
                        'path' => $path,
                        'is_primary' => $index === 0, // Primera imagen como principal
                    ]);
                }
            }

            DB::commit();

            return [
                'data' => $product->load('product_imagens'),
                'error' => null,
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error("Error al crear el producto: " . $e->getMessage());

            return [
                'data' => null,
                'error' => 'Error al crear el producto: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Actualizar un producto existente
     */
    public function updateProduct(int $productId, array $data, array $newImages = [], array $imagesToDelete = []): array
    {
        DB::beginTransaction();

        try {
            // Buscar producto
            $product = $this->findId($productId);

            if (!$product) {
                return [
                    'data' => null,
                    'error' => 'Producto no encontrado.',
                ];
            }

            // Actualizar datos del producto
            $product = $this->productRepository->update($productId, [
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'price' => $data['price'],
                'stock' => $data['stock'],
                'category_id' => $data['category_id'],
                'is_active' => $data['is_active'] ?? true,
                'sku' => $data['sku'] ?? null,
            ]);

            // Eliminar imágenes marcadas
            if (!empty($imagesToDelete)) {
                foreach ($imagesToDelete as $imageId) {
                    $image = ProductImagens::find($imageId);
                    if ($image) {
                        $this->fileService->deleteFile($image->path);
                        $image->delete();
                    }
                }
            }

            // Subir nuevas imágenes
            if (!empty($newImages)) {
                foreach ($newImages as $image) {
                    $path = $this->fileService->upload($image, 'products');
                    Log::info('Producto actualizado');
                    Log::info($path);
                    ProductImagens::create([
                        'product_id' => $product->id,
                        'path' => $path,
                        'is_primary' => false,
                    ]);
                }
            }

            DB::commit();

            return [
                'data' => $product->load('product_imagens'),
                'error' => null,
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error("Error al actualizar el producto con ID {$productId}: " . $e->getMessage());

            return [
                'data' => null,
                'error' => 'Error al actualizar el producto: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Cambiar estado activo/inactivo del producto
     */
    public function toggleStatus(int $productId): array
    {
        Log::error("toggleStatus");
        try {
            $product = $this->findId($productId);

            if (!$product) {
                return [
                    'data' => null,
                    'error' => 'Producto no encontrado.',
                ];
            }

            $product->is_active = !$product->is_active;
            $product->save();

            return [
                'data' => $product,
                'error' => null,
                'status' => $product->is_active ? 'activado' : 'desactivado',
            ];
        } catch (\Exception $e) {
            Log::error("Error al cambiar estado del producto con ID {$productId}: " . $e->getMessage());

            return [
                'data' => null,
                'error' => 'Error al cambiar el estado del producto: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Eliminar un producto y sus imágenes asociadas
     */
    public function deleteProduct(int $productId): array
    {
        DB::beginTransaction();

        try {
            $product = $this->findId($productId);

            if (!$product) {
                return [
                    'data' => null,
                    'error' => 'Producto no encontrado.',
                ];
            }

            // Verificar si tiene órdenes asociadas
            if ($product->orderItems()->count() > 0) {
                return [
                    'data' => null,
                    'error' => 'No se puede eliminar el producto porque tiene órdenes asociadas.',
                ];
            }

            // Eliminar imágenes del storage
            foreach ($product->product_imagens as $image) {
                $this->fileService->deleteFile($image->path);
                $image->delete();
            }

            // Eliminar producto
            $deletedProduct = $this->productRepository->delete($productId);

            DB::commit();

            return [
                'data' => $deletedProduct,
                'error' => null,
            ];
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error("Error al eliminar el producto con ID {$productId}: " . $e->getMessage());

            return [
                'data' => null,
                'error' => 'Error al eliminar el producto: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Desactivar producto (soft delete)
     */
    public function deactivateProduct(int $productId): array
    {
        try {
            $product = $this->findId($productId);

            if (!$product) {
                return [
                    'data' => null,
                    'error' => 'Producto no encontrado.',
                ];
            }

            $product->is_active = false;
            $product->save();

            return [
                'data' => $product,
                'error' => null,
            ];
        } catch (\Exception $e) {
            Log::error("Error al desactivar el producto con ID {$productId}: " . $e->getMessage());

            return [
                'data' => null,
                'error' => 'Error al desactivar el producto: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Activar producto
     */
    public function activeProduct(int $productId): array
    {
        try {
            $product = $this->findId($productId);

            if (!$product) {
                return [
                    'data' => null,
                    'error' => 'Producto no encontrado.',
                ];
            }

            $product->is_active = true;
            $product->save();

            return [
                'data' => $product,
                'error' => null,
            ];
        } catch (\Exception $e) {
            Log::error("Error al activar el producto con ID {$productId}: " . $e->getMessage());

            return [
                'data' => null,
                'error' => 'Error al activar el producto: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Establecer una imagen como principal
     */
    public function setPrimaryImage(int $productId, int $imageId): array
    {
        try {
            // Quitar todas las imágenes principales del producto
            ProductImagens::where('product_id', $productId)
                ->update(['is_primary' => false]);

            // Establecer la nueva imagen principal
            $image = ProductImagens::where('product_id', $productId)
                ->where('id', $imageId)
                ->first();

            if (!$image) {
                return [
                    'data' => null,
                    'error' => 'Imagen no encontrada.',
                ];
            }

            $image->is_primary = true;
            $image->save();

            return [
                'data' => $image,
                'error' => null,
            ];
        } catch (\Exception $e) {
            Log::error("Error al establecer imagen principal: " . $e->getMessage());

            return [
                'data' => null,
                'error' => 'Error al establecer imagen principal: ' . $e->getMessage(),
            ];
        }
    }
}
