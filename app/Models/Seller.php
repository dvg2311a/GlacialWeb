<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'surname', 'direction', 'phone', 'sex', 'identity_card', 'picture',
        'cart_id'
    ];

    public function cart(){
        return $this -> belongsTo(Cart::class);
    }

    public function sellerDailyReport(){
        return $this -> hasMany(SellerDailyReport::class);
    }
}
