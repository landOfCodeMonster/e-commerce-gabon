<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'reference',
        'status',
        'subtotal',
        'service_fee',
        'shipping_fee',
        'profit_margin',
        'total',
        'currency',
        'notes',
        'admin_notes',
    ];

    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
            'subtotal' => 'decimal:2',
            'service_fee' => 'decimal:2',
            'shipping_fee' => 'decimal:2',
            'profit_margin' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class);
    }

    public static function generateReference(): string
    {
        $date = now()->format('Ymd');
        $random = strtoupper(substr(uniqid(), -4));
        return "ORD-{$date}-{$random}";
    }

    public function transitionTo(OrderStatus $newStatus, ?int $changedBy = null, ?string $comment = null): void
    {
        $oldStatus = $this->status;

        if (!$oldStatus->canTransitionTo($newStatus)) {
            throw new \InvalidArgumentException(
                "Transition de '{$oldStatus->label()}' vers '{$newStatus->label()}' non autorisée."
            );
        }

        $this->update(['status' => $newStatus]);

        $this->statusHistories()->create([
            'old_status' => $oldStatus->value,
            'new_status' => $newStatus->value,
            'changed_by' => $changedBy,
            'comment' => $comment,
        ]);
    }

    public function calculateTotals(): void
    {
        $subtotal = $this->items->sum(function ($item) {
            return ($item->converted_price ?? $item->scraped_price ?? 0) * $item->quantity;
        });

        $serviceFeePercent = Setting::get('default_service_fee_percent', 10);
        $serviceFee = $subtotal * ($serviceFeePercent / 100);

        $this->update([
            'subtotal' => $subtotal,
            'service_fee' => $serviceFee,
            'total' => $subtotal + $serviceFee + $this->shipping_fee + $this->profit_margin,
        ]);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByStatus($query, OrderStatus $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Frais de service "tout compris" visible par le client.
     * Regroupe : frais de service + frais de livraison + marge bénéficiaire.
     */
    public function getServiceFeeTotalAttribute(): float
    {
        return ($this->service_fee ?? 0) + ($this->shipping_fee ?? 0) + ($this->profit_margin ?? 0);
    }

    public function isPaid(): bool
    {
        return $this->payments()->where('status', 'completed')->exists();
    }
}
