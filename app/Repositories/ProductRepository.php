<?php

namespace App\Repositories;

use App\Models\products as Product;
use App\Repositories\Contracts\ProductRepositoryInterface;


class ProductRepository implements ProductRepositoryInterface
{

  protected $model;

  public function __construct(Product $model)
  {
    $this->model = $model;
  }

  public function all(): array|null
  {
    $products = $this->model->with('product_imagens')->get();
    return $products->toArray();
  }

  public function findId($id):Product|null
  {
    return $this->model->with('product_imagens')->find($id);
  }

  public function discount_stok(
    $product_id,
    $quantity
  )
  {
     
    $products = $this->model->where('id', $product_id)->first();

    if ($products->stock < $quantity) {
      return null;
    }

    $products->stock -= $quantity;
    $products->save();
    return $products;
  }

  
}
