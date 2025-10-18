<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethods extends Model
{
    public $fillable = [
        'name',
        'details',
        'is_active',
    ];
}
