<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function getServiceRatesFormatedAttribute()
    {
        return number_format($this->service_rates);
    }
}
