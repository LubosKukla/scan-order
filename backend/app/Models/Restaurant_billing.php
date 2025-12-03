<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restaurant_billing extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_id',
        'plan_id',
        'ico',
        'dic',
        'ic_dph',
        'iban',
        'trial_ends_at',
        'subscription_status',
        'company_name'
    ];

    protected $casts = [
        'trial_ends_at' => 'date',
    ];

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
