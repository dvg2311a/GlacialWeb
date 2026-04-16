<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrivateSaleDetail extends Model
{


    public function product() : BelongsTo {
        return $this -> belongsTo(Product::class);
    }

    public function privateSale() : BelongsTo {
        return $this->belongsTo(PrivateSale::class);
    }
}
