<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Inventory extends Model
{
    use HasFactory;

    protected $appends = ['available_quantity', 'is_low_stock'];

    protected $fillable = [
        'stock',
        'committed_quantity',
        'in_transit_quantity',
        'low_stock_threshold',
        'product_id',
    ];

    protected function casts(): array
    {
        return [
            'stock' => 'integer',
            'committed_quantity' => 'integer',
            'in_transit_quantity' => 'integer',
            'low_stock_threshold' => 'integer',
        ];
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    public function getIsLowStockAttribute(): bool
    {
        return $this->available_quantity <= $this->low_stock_threshold;
    }

    public function getAvailableQuantityAttribute(): int
    {
        return max(0, $this->stock - $this->committed_quantity);
    }
}
