<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plate extends Model
{
    use HasFactory;

    protected $fillable = [
        'code_plate', 'plate_dimension_id', 'freezer_id'
    ];

    public function plateDimension(){
        return $this -> belongsTo(PlateDimension::class);
    }

    public function freezer(){
        return $this -> belongsTo(Freezer::class);
    }
}
