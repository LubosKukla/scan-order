<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu_item extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id',
        'name',
        'type',
        'description',
        'base_price',
        'time_to_prepare',
        'weight',
        'kcal',
        'category',
        'is_active',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function itemVariant()
    {
        return $this->hasMany(Item_variant::class);
    }

    public function menuItemAlergen()
    {
        return $this->hasMany(menu_item_alergen::class);
    }
}
