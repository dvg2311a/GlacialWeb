<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerDailyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_date', 'morning_checkup', 'evening_checkup', 'grand_total',
        'seller_id'
    ];

    protected $casts = [
        'report_date' => 'date',
    ];

    public function seller(){
        return $this -> belongsTo(Seller::class);
    }

    public function sellerDailyReportDetail(){
        return $this -> hasMany(SellerDailyReportDetail::class);
    }
}
