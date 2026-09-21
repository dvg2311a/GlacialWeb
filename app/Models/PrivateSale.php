<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PrivateSale extends Model
{
    protected $fillable = [
        'date_sale',
        'grand_total',
        'seller_id'
    ];

    protected $casts = [
        'date_sale' => 'date',
    ];
    public function privateSaleDetail(): HasMany
    {
        return $this->hasMany(PrivateSaleDetail::class);
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }
}
