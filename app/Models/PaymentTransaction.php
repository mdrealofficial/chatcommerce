<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    protected $fillable = [
        'order_id',
        'transaction_id',
        'payment_method',
        'amount',
        'status',
        'payment_url',
        'payment_response',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'payment_response' => 'array',
            'paid_at' => 'datetime',
        ];
    }

    // Relationships
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
