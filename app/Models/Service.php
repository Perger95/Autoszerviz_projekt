<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    public function car()
{
    return $this->belongsTo(Car::class, 'car_id', 'car_id')->whereColumn('client_id', 'client_id');
}

}
