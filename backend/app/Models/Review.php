<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_item_id',
        'customer_id',
        'restaurant_id',
        'type',
        'text',
        'rating',
    ];

    public function menuItem()
    {
        return $this->belongsTo(Menu_item::class);
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
