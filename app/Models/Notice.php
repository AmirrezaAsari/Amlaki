<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    protected $fillable = [
        'title',
        'description',
        'type',
        'price_per_meter',
        'total_price',
        'pre_pay',
        'monthly_pay',
        'meterage',
        'phone_number',
        'city',
        'district',
    ];
}
