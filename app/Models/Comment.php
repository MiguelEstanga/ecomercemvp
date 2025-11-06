<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public $fillable = [
        'user_id',
        'product_id',
        'content'
    ];
}
