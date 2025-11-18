<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    public $fillable = [
        'avatar',
        'phone',
        'country',
        'city',
        'address',
        'dni',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function getAvatarUrlAttribute()
    {
        
        if ($this->avatar && $this->avatar) {
            $cleanPath =  str_replace('public/', '', $this->avatar);
            $path = asset('storage/' . $cleanPath);
            return $path;
        }

        // Avatar por defecto con iniciales
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->user->name) . '&color=7F9CF5&background=EBF4FF';
    }
}
