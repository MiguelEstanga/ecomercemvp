<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    protected $table = 'orders';
    public $fillable = [
        'user_id',
        'order_number',
        'total_amount',
        'status',
        'payment_method_id',    
        'pickup_agency_id',
        'shipping_address',
        'observaciones',
        'imagen_documento',
        'imagen_comprobante',
        'phone_number',
        'reference_number',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
