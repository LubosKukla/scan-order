<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Basket_item extends Model
{
    use HasFactory;

    protected $fillable = [
        'basket_id',
        'menu_item_id',
        'item_variant_id',
        'quantity',
        'price',
        'note',
    ];

    public function basket()
    {
        return $this->belongsTo(Basket::class);
    }

    public function menuItem()
    {
        return $this->belongsTo(Menu_item::class);
    }

    public function itemVariant()
    {
        return $this->belongsTo(Item_variant::class);
    }
}
