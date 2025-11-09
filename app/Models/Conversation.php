<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = [
        'user_id',
        'customer_id',
        'page_id',
        'last_message_at',
        'is_read',
    ];

    protected function casts(): array
    {
        return [
            'last_message_at' => 'datetime',
            'is_read' => 'boolean',
        ];
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function latestMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }
}
