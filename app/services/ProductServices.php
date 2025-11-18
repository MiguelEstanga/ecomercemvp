<?php

namespace app\services;


use App\Repositories\ProductRepository;
use App\services\FileService;
use Illuminate\Support\Facades\Log;




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
  public function getAllProducts()
  {
    return $this->productRepository->all();
  }

  public function findId($id)
  {
    $product = $this->productRepository->findId($id);

    if (!$product) {
      return null;
    }

    return $product;
  }

  public function discount_stok(
    $product_id,
    $quantity
  ) {
    $product = $this->productRepository->discount_stok(
      $product_id,
      $quantity
    );


    return $product;
  }

  public function buscarPorNombre($nombre)
  {
    $product = $this->productRepository->buscarPorNombre($nombre);

    return $product;
  }

  public function create($data)
  {
    $product = $this->productRepository->create($data);
    return $product;
  }

  public function update($product_id, $data)
  {
    $product = $this->productRepository->update($product_id, $data);
    return $product;
  }

  /**
   * Elimina un producto de la base de datos y sus archivos asociados.
   *
   * @param int $productId El ID del producto a eliminar.
   * @return array Devuelve un array con 'data' (producto eliminado) o 'error'.
   */
  public function delete(int $productId): array
  {
    try {
      // 1. Obtener el producto antes de eliminarlo para acceder a sus relaciones (imágenes)
      // La implementación del Repositorio debe asegurar que las imágenes se carguen (eager loading)
      $product = $this->productRepository->findId($productId);

      if (!$product) {
        // Manejar el caso de que el producto no exista
        return [
          'data' => null,
          'error' => 'Producto no encontrado.',
        ];
      }

      // 2. Verificar y eliminar archivos asociados (imágenes)
      // Nota: Asume que 'product_imagens' es la relación que devuelve una colección
      if ($product->product_imagens->isNotEmpty()) {

        // Recopilar los paths de las imágenes para pasárselos al servicio de archivos
        $pathsToDelete = $product->product_imagens->pluck('path')->toArray();


        $this->fileService->deleteMultiple($pathsToDelete);
      }

      // 3. Eliminar el producto de la base de datos
      // Se llama después de la eliminación de archivos para tener el objeto completo en caso de error
      $deletedProduct = $this->productRepository->delete($productId);

      // Si la eliminación fue exitosa, devolvemos el objeto que fue eliminado
      return [
        'data' => $deletedProduct,
        'error' => null,
      ];
    } catch (\Exception $e) {
      // 4. Captura y Manejo de Errores
      // Registra el error para depuración
      Log::error("Error al eliminar el producto con ID {$productId}: " . $e->getMessage());

      return [
        'data' => null,
        'error' => 'Error al procesar la eliminación: ' . $e->getMessage(),
      ];
    }
  }
}
