<?php
namespace App\Repositories\Contracts;
use App\Models\products as Product;  

interface ProductRepositoryInterface
{
    /**
     * Recupera todos los productos.
     *
     * @return Product[]|null
     */
    public function all(): array|null; // (2) El tipo de retorno debe ser 'array' o 'null'
    public function findId($id):Product|null;
}