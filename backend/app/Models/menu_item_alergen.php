<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class menu_item_alergen extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_item_id',
        'alergen_id',
    ];

    public function menuItem()
    {
        return $this->belongsTo(Menu_item::class);
    }

    public function alergen()
    {
        return $this->belongsTo(Alergen::class);
    }
}
