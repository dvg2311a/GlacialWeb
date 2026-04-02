<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeFreezer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function freezer(){
        return $this -> hasMany(Freezer::class);
    }
}
