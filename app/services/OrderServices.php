<?php

namespace App\services;


use App\Repositories\OrderRpository;
use App\services\ProductServices;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class OrderServices
{
  private $orderRepository;
  private $productServices;
  public function __construct(
    OrderRpository $orderRepository,
    ProductServices $productServices
  ) {
    $this->orderRepository = $orderRepository;
    $this->productServices = $productServices;
  }

  public function getOrders()
  {
    return $this->orderRepository->getOrder();
  }

  public function getOrdersUser($id)
  {
    return $this->orderRepository->getOrderUsers($id);
  }

  public function create($entryData)
  {
    $data = $entryData;
    $metadata = [];

    $storagePath = 'order_documents';
    $disk = 'public';

    //document
    $docPath = uploadAndGetPath($data, 'document_image', $storagePath, $disk);
    $metadata['imagen_documento'] = $docPath;
    if ($docPath) {
      $uploadedPaths[] = $docPath; // Guardamos la ruta
    }

    // Comprobante
    $proofPath = uploadAndGetPath($data, 'payment_proof', $storagePath, $disk);
    $metadata['imagen_comprobante'] = $proofPath;
    if ($proofPath) {
      $uploadedPaths[] = $proofPath; // Guardamos la ruta
    }

    try {

      $order = DB::transaction(function () use ($data, $metadata) {


        $product = $this->productServices
          ->discount_stok($data['product_id'], $data['quantity']);

        if (!$product) {
          return response()->json(['error' => 'Insufficient stock for the product'], 400);
        }


        $metadata['user_id'] = $data['user_id'];
        $metadata['order_number'] =  $this->orderRepository->getOrderNumber();
        $metadata['total_amount'] = $product->price * $data['quantity'];
        $metadata['status'] = 'pending';
        $metadata['payment_method_id'] = $data['payment_method_id'];
        $metadata['pickup_agency_id'] = $data['pickup_agency_id'];
        $metadata['shipping_address'] = $data['shipping_address'];
        $metadata['observaciones'] = "comprado";
        $metadata['phone_number'] = $data['phone_number'];
        $metadata['reference_number'] = $data['reference_number'];
        // 3c. Creación de Orden
        $order = $this->orderRepository->createOrder($metadata);

        // 3d. Creación del Ítem de Orden
        $orderItemData = [
          'order_id' => $order->id,
          'product_id' => $product->id,
          'product_name' => $product->name,
          'quantity' => $data['quantity'],
          'unit_price' => $product->price,
        ];

        $this->orderRepository->addOrderItem($orderItemData);
        return $order;
      });


      return response()->json(['message' => 'Order created successfully', 'order' => $order], 201);
    } catch (Exception $e) {
      if (!empty($uploadedPaths)) {
        Storage::disk($disk)->delete($uploadedPaths);
      }
      Log::error('Error in OrderServices create: ' . $e->getMessage());
      return response()->json(['error' => 'Failed to create order: ' . $e->getMessage()], 500);
    }
  }

  /**
   * Obtener todas las órdenes del usuario autenticado
   */
  public function getUserOrders(?int $id = null)
  {
    $userId = $id;
    return $this->orderRepository->getByUser($userId);
  }

  /**
   * Obtener órdenes paginadas del usuario
   */
  public function getUserOrdersPaginated(?int $id = null, int $perPage = 10)
  {
    $userId = $id;
    return $this->orderRepository->paginateByUser($userId, $perPage);
  }


  /**
   * Obtener orden por ID verificando permisos
   */
  public function getOrderForUser(int $orderId, int $id  )
  {
     $userId = $id;
    $order = $this->orderRepository->findById($orderId);
    if (!$order || $order->user_id !== $userId) {
      return null;
    }
    return $order;
  }
}
