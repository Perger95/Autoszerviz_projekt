<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function services()
    {
        return $this->hasMany(Service::class, 'car_id', 'car_id')
                    ->whereColumn('client_id', 'client_id')
                    ->orderBy('lognumber', 'desc');
    }


}
