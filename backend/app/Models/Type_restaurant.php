<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Type_restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
    ];

    public function restaurants()
    {
        return $this->hasMany(Restaurant::class);
    }
}
