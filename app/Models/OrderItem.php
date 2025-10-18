<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    public $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'unit_price',
        'quantity',
    ];

    public function product()
    {
        return $this->belongsTo(products::class);
    }

    public function order()
    {
        return $this->belongsTo(Orders::class);
    }

    // app/Models/Order.php

    public function items()
    {
        // Forzamos el uso de 'orders_id' en lugar de 'order_id'
        return $this->hasMany(OrderItem::class, 'orders_id');
    }
}
