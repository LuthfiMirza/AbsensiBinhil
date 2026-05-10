<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    protected $fillable = [
        'name', 'unit', 'minimum_stock', 'description', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function transactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    public function getCurrentStockAttribute(): int
    {
        if ($this->relationLoaded('transactions')) {
            return $this->stockFromTransactions($this->transactions);
        }

        return $this->stockFromTransactions($this->transactions()->get(['type', 'quantity']));
    }

    public function getIsLowStockAttribute(): bool
    {
        return $this->current_stock <= $this->minimum_stock;
    }

    private function stockFromTransactions($transactions): int
    {
        $incoming = $transactions->where('type', InventoryTransaction::TYPE_IN)->sum('quantity');
        $outgoing = $transactions->whereIn('type', [InventoryTransaction::TYPE_OUT, InventoryTransaction::TYPE_ALLOCATION])->sum('quantity');

        return max((int) $incoming - (int) $outgoing, 0);
    }
}
