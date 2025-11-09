<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'page_id',
        'page_access_token',
        'page_name',
        'page_profile_image',
        'is_connected',
        'token_expires_at',
    ];

    protected function casts(): array
    {
        return [
            'is_connected' => 'boolean',
            'token_expires_at' => 'datetime',
        ];
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }
}