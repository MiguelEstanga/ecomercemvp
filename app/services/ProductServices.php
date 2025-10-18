<?php

namespace app\services;

use App\Repositories\ProductRepository;

class ProductServices
{
  protected $productRepository;

  public function __construct(ProductRepository $productRepository)
  {
    $this->productRepository = $productRepository;
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
}
