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

    public function customer()
    {
        return $this->hasMany(Customer::class);
    }

    public function restaurant()
    {
        return $this->hasMany(Restaurant::class);
    }
}
