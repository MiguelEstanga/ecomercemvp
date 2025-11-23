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
    $products = $this->model->where('is_active', true)->with('product_imagens')->get();
    return $products->toArray();
  }

  public function findId($id): Product|null
  {
    return $this->model->with('product_imagens')->find($id);
  }

  public function discount_stok(
    $product_id,
    $quantity
  ) {

    $products = $this->model->where('id', $product_id)->first();

    if ($products->stock < $quantity) {
      return null;
    }

    $products->stock -= $quantity;
    $products->save();
    return $products;
  }

  public function buscarPorNombre($nombre)
  {
    return $this->model->where('name', "like", "%{$nombre}%")->get();
  }

  public function create($data): Product
  {
    $product = new Product();
    $product->name = $data['name'];
    $product->slug = $data['slug'];
    $product->description = $data['description'];
    $product->price = $data['price'];
    $product->stock = $data['stock'];

    $product->save();

    return $product;
  }

  public function update($product_id, $data): Product
  {
    $product = $this->model->find($product_id);
    $product->name = $data['name'];
    $product->slug = $data['slug'];
    $product->description = $data['description'];
    $product->price = $data['price'];
    $product->stock = $data['stock'];
    $product->is_active = $data['is_active'];

    $product->save();

    return $product;
  }

  public function delete($product_id): Product
  {
    $product = $this->findId($product_id);
    $product->is_active = false;
    $product->save();

    return $product;
  }
}
