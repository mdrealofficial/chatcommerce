<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'conversation_id',
        'message_id',
        'sender_type',
        'message',
        'attachments',
        'is_read',
    ];

    protected function casts(): array
    {
        return [
            'attachments' => 'array',
            'is_read' => 'boolean',
        ];
    }

    // Relationships
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }
}
