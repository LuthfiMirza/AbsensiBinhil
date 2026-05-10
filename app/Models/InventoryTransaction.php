<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryTransaction extends Model
{
    public const TYPE_IN = 'in';
    public const TYPE_OUT = 'out';
    public const TYPE_ALLOCATION = 'allocation';

    protected $fillable = [
        'inventory_item_id', 'employee_id', 'transaction_date', 'type', 'quantity', 'area', 'source', 'notes',
    ];

    protected $casts = [
        'transaction_date' => 'date',
    ];

    public function item()
    {
        return $this->belongsTo(InventoryItem::class, 'inventory_item_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public static function typeLabels(): array
    {
        return [
            self::TYPE_IN => 'Stok Masuk',
            self::TYPE_OUT => 'Stok Keluar',
            self::TYPE_ALLOCATION => 'Alokasi',
        ];
    }

    public function getTypeLabelAttribute(): string
    {
        return self::typeLabels()[$this->type] ?? $this->type;
    }
}
