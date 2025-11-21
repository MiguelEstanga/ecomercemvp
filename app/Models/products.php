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
        'active',
    ];

    public function product_imagens()
    {
        return $this->hasMany(ProductImagen::class, 'product_id', 'id');
    }

    public function mainImage()
    {   
        if($this->product_imagens[0]->path === null){
            return null;
        }
         $cleanPath = str_replace('public/', '', $this->product_imagens[0]->path);
        return $cleanPath;
    }
}
