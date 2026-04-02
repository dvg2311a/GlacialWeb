<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerDailyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_date', 'morning_checkup', 'evening_checkup',
        'seller_id'
    ];

    public function seller(){
        return $this -> belongsTo(Seller::class);
    }

    public function sellerDailyReportDetail(){
        return $this -> hasMany(SellerDailyReportDetail::class);
    }
}
