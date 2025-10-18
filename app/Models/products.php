<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class products extends Model
{
    protected $table = 'products';
    public $fillable = [
        'name',
        'slug',
        'description',
        'price',    
        'price',
        'stok',
        'is_active',
    ];

    public function product_imagens()
    {
        return $this->hasMany(ProductImagen::class, 'product_id', 'id');
    }
}
