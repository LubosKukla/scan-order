<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alergen extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function menuItemAlergen()
    {
        return $this->hasMany(menu_item_alergen::class);
    }
}
