<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PrivateSale extends Model
{
    protected $fillable = [
        'date_sale', 'grand_total', 'user_id'
    ];
    public function privateSaleDetail() : HasMany {
        return $this ->hasMany(PrivateSaleDetail::class);
    }
}
