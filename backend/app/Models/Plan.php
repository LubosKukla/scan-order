<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price_monthly',
        'description'
    ];

    public function restaurantBillings()
    {
        return $this->hasMany(Restaurant_billing::class);
    }
}
