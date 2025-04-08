<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;

class CarController extends Controller
{
    public function services($id)
    {
        $services = Service::where('car_id', $id)->orderByDesc('lognumber')->get();
        return response()->json($services);
    }

    public function getCarServices($clientId, $carId)
    {
        $services = Service::where('client_id', $clientId)
                           ->where('car_id', $carId)
                           ->orderBy('lognumber', 'desc')
                           ->get();

        return response()->json($services);
    }
}
