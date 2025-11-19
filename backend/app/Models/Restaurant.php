<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
        'ico',
        'dic',
        'dic_dph',
        'iban',
        'is_active',
        'name',
        'name_boss',
        'type_restaurant_id',
        'description',
        'logo_path',
        'address_id',
        'user_id',
        'pocet_stolov',
    ];

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function openHours()
    {
        return $this->hasMany(Open_Hour::class);
    }

    public function baskets()
    {
        return $this->hasMany(Basket::class);
    }

    public function menuItems()
    {
        return $this->hasMany(Menu_item::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function workers()
    {
        return $this->hasMany(Worker::class);
    }

    public function typeRestaurant()
    {
        return $this->belongsTo(Type_restaurant::class);
    }
}
