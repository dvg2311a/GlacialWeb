<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerDailyReportDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'quantity_out', 'quantity_return', 'quantity_sold',
        'total_sales', 'wholesale_price', 'seller_daily_report_id',
        'product_id', 'user_id'
    ];

    public function sellerDailyReport(){
        return $this -> belongsTo(SellerDailyReport::class);
    }

    public function product(){
        return $this -> belongsTo(Product::class);
    }

    public function user(){
        return $this -> belongsTo(User::class);
    }
}
