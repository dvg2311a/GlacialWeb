<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'boxes_ordered', 'boxes_received', 'purchase_price_per_box',
        'tax_percentage', 'tax_amount_per_box', 'purchase_price_total_per_box',
        'order_enterprise_id', 'product_id'
    ];

    public function orderEnterprise(){
        return $this -> belongsTo(OrderEnterprise::class);
    }

    public function product(){
        return $this -> belongsTo(Product::class);
    }
}
