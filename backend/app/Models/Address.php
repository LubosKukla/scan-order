<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'street',
        'number_of_building',
        'PSC',
        'city',
    ];

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function restaurants()
    {
        return $this->hasMany(Restaurant::class);
    }
}
