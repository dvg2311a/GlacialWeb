<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'inventory_id',
        'product_id',
        'movement_type',
        'quantity',
        'stock_before',
        'stock_after',
        'committed_before',
        'committed_after',
        'reference_type',
        'reference_id',
        'movement_key',
        'reversal_of_id',
        'user_id',
        'notes',
    ];

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function reversalOf(): BelongsTo
    {
        return $this->belongsTo(self::class, 'reversal_of_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
