<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'user_id',
        'psid',
        'name',
        'profile_pic',
        'phone',
        'address',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
