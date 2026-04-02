<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderEnterprise extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_date', 'expected_date', 'order_status',
        'total_tax', 'subtotal_base', 'grand_total'
    ];

    public function purchaseOrderDetail(){
        return $this -> hasMany(PurchaseOrderDetail::class);
    }
}
