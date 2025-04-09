<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Car;

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
    ->get()
    ->map(function ($service) {
        if ($service->event === 'regisztralt') {
            $car = Car::where('client_id', $service->client_id)
                      ->where('car_id', $service->car_id)
                      ->first();
            $service->registered = $car->registered ?? null;
        }
        return $service;
    });

    return response()->json($services);
}

}
