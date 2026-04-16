<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'shape', 'wholesale_price', 'retail_price',
        'purchase_price', 'bad_price', 'expiration_date','freezer_id',
        'type_product_id', 'flavor_id', 'status_product_id'
    ];

    public function freezer(){
        return $this -> belongsTo(Freezer::class);
    }

    public function typeProduct(){
        return $this -> belongsTo(TypeProduct::class);
    }

    public function flavor(){
        return $this -> belongsTo(FlavorProduct::class);
    }

    public function statusProduct(){
        return $this -> belongsTo(StatusProduct::class);
    }

    public function purchaseOrderDetail(){
        return $this -> hasMany(PurchaseOrderDetail::class);
    }

    public function sellerDailyReportDetail(){
        return $this -> hasMany(SellerDailyReportDetail::class);
    }

    public function inventory(){
        return $this -> hasOne(Inventory::class);
    }

    public function privateSaleDetail() : HasMany {
        return $this->hasMany(PrivateSaleDetail::class);
    }

}
