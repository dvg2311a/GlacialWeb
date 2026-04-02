<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlateDimension extends Model
{
    use HasFactory;

    protected $fillable = [
        'height', 'width', 'unit_measure', 'shape'
    ];

    public function plate(){
        return $this -> hasMany(Plate::class);
    }
}
