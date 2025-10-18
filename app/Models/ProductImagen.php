<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\products as Product;
class ProductImagen extends Model
{
    public $fillable = [
        'product_id',
        'path',
        'is_main',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
