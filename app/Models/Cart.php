<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'plate_number', 'description', 'proprietary_type',
        'type_cart_id', 'status_cart_id', 'seller_id',
    ];

    public function typeCart(){
        return $this -> belongsTo(TypeCart::class);
    }

    public function statusCart(){
        return $this -> belongsTo(StatusCart::class);
    }

    public function seller(){
        return $this -> hasOne(Seller::class);
    }
}
