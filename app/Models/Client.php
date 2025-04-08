<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    public function cars()
    {
        return $this->hasMany(Car::class, 'client_id');
    }

    public function services()
    {
        return $this->hasMany(Service::class, 'client_id');
    }
}
