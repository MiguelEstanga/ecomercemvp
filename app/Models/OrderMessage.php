<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderMessage extends Model
{
    protected $fillable = [
        'order_id',
        'user_id',
        'sender_type',
        'message',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Orders::class, 'order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Scope para mensajes no leídos
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }
    
    // Scope para mensajes de un tipo específico
    public function scopeFromType($query, $type)
    {
        return $query->where('sender_type', $type);
    }
}