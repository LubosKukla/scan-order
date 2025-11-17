<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item_variant extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_item_id',
        'name',
        'price',
        'weight',
        'kcal',
        'is_active',
    ];

    public function menuItem()
    {
        return $this->belongsTo(Menu_item::class);
    }
}
