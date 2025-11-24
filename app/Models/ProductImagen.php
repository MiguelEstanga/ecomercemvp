<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
class ProductImagen extends Model
{  
    public $fillable = [
        'product_id',
        'path',
        'is_main',
    ];

    protected $casts = [
        'is_main' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getUrlAttribute()
    {
        $cleanPath = str_replace('public/', '', $this->path);
        return asset('storage/' . $cleanPath);
    }
}
