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

    protected $casts = [
        'total_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relación con items de la orden
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    // Relación con usuario
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación con método de pago
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethods::class, 'payment_method_id');
    }

    // Relación con agencia de retiro
    public function pickupAgency()
    {
        return $this->belongsTo(PinchupAgencies::class, 'pickup_agency_id');
    }

    // Scopes útiles
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeShipped($query)
    {
        return $query->where('status', 'shipped');
    }

    // Accessor para formato de estado
    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => '🟡 Pendiente',
            'processing' => '🔵 Procesando',
            'shipped' => '🟣 Enviado',
            'completed' => '🟢 Completado',
            'cancelled' => '🔴 Cancelado',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    // Accessor para badge color
    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'processing' => 'bg-blue-100 text-blue-800',
            'shipped' => 'bg-purple-100 text-purple-800',
            'completed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
        ];

        return $colors[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    public function messages()
    {
        return $this->hasMany(OrderMessage::class, 'order_id');
    }

    public function unreadMessages()
    {
        return $this->hasMany(OrderMessage::class, 'order_id')->where('is_read', false);
    }
}
