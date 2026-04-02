<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Freezer extends Model
{
    use HasFactory;

    protected $fillable = [
        'number_freezer', 'status', 'type_freezer_id'
    ];

    public function typeFreezer(){
        return $this -> belongsTo(TypeFreezer::class);
    }
}
