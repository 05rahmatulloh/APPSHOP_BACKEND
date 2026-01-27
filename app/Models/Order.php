<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_code',

        // pricing snapshot
        'subtotal',
        'discount_total',
        'shipping_cost',
        'total',

        // payment
        'payment_method',
        'payment_channel',
        'payment_reference',
        'payment_status',

        // order lifecycle
        'status',

        // shipping
        'shipping_address',
        'tracking_number',

        // timestamps
        'paid_at',
        'shipped_at',
        'completed_at',
        'cancelled_at',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'shipped_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    /* ======================
     |  RELATIONS
     ====================== */

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /* ======================
     |  HELPERS (OPTIONAL)
     ====================== */

    public function markAsPaid(array $payload = []): void
    {
        $this->update([
            'payment_status' => 'paid',
            'status' => 'processing',
            'payment_channel' => $payload['payment_channel'] ?? null,
            'payment_reference' => $payload['payment_reference'] ?? null,
            'paid_at' => now(),
        ]);
    }

    public function markAsFailed(): void
    {
        $this->update([
            'payment_status' => 'failed',
        ]);
    }

    public function markAsExpired(): void
    {
        $this->update([
            'payment_status' => 'expired',
        ]);
    }
}
