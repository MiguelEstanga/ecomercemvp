<?php

namespace App\Repositories;

use App\Models\Orders;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class OrderRpository
{
  private $order;
  private $orderItem;
  public function __construct(Orders $order, OrderItem $orderItem)
  {
    $this->order = $order;
    $this->orderItem = $orderItem;
  }

  public function getOrder()
  {
    return $this->order->all();
  }

  public function all(array $relations = ['items.product', 'user'])
  {
    return $this->order->with($relations)->get();
  }

  public function paginate(int $perPage = 15, array $relations = ['items.product'])
  {
    return $this->order->with($relations)
      ->orderBy('created_at', 'desc')
      ->paginate($perPage);
  }

  /**
   * Buscar orden por ID con relaciones
   */
  public function findById(int $id, array $relations = ['items.product', 'user']): ?Orders
  {
    return $this->order->with($relations)->find($id);
  }

  /**
   * Buscar orden por ID o lanzar excepción
   */
  public function findByIdOrFail(int $id, array $relations = ['items.product']): Orders
  {
    return $this->order->with($relations)->findOrFail($id);
  }

  public function createOrder($data)
  {
    return DB::transaction(function () use ($data) {
      $order = $this->order->create($data);
      return $order;
    });
  }

  public function getOrderById($id)
  {
    return $this->order->with('items')->find($id);
  }

  public function updateOrder($id, $data)
  {
    return DB::transaction(function () use ($id, $data) {
      $order = $this->order->find($id);
      if ($order) {
        $order->update($data);
        return $order;
      }
      return null;
    });
  }

  public function deleteOrder($id)
  {
    return DB::transaction(function () use ($id) {
      $order = $this->order->find($id);
      if ($order) {
        $order->delete();
        return true;
      }
      return false;
    });
  }

  public function getOrderItems($id)
  {
    return $this->order->find($id)->items;
  }

  public function addOrderItem($data)
  {
    $this->orderItem->create($data);
  }

  public function getOrderNumber()
  {
    return $this->order::count() + 1;
  }

  public function getOrderUsers($id)
  {
    return $this->order->where('user_id', $id)->get();
  }

  /**
   * Obtener órdenes por usuario
   */
  public function getByUser(int $userId, array $relations = ['items.product'])
  {
    return $this->order->where('user_id', $userId)
      ->with($relations)
      ->orderBy('created_at', 'desc')
      ->get();
  }

  /**
     * Obtener órdenes paginadas por usuario
     */
    public function paginateByUser(int $userId, int $perPage = 10) 
    {
        return $this->order->where('user_id', $userId)
            ->with(['items.product'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }
}
