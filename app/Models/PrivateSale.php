<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PrivateSale extends Model
{
    public function privateSaleDetail() : HasMany {
        return $this ->hasMany(PrivateSaleDetail::class);
    }
}
