<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Open_Hour extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id',
        'day_of_week',
        'open_time',
        'close_time',
        'is_closed',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
