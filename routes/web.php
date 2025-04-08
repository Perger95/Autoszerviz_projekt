<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\CarController;

// Frontend nézet
Route::get('/', function () {
    return view('homepage');
});

// API endpointok
Route::get('/api/clients', [ClientController::class, 'index']);
Route::get('/api/clients/{id}/cars', [ClientController::class, 'cars']);
Route::get('/api/cars/{id}/services', [CarController::class, 'services']);
Route::get('/api/clients/{clientId}/cars/{carId}/services', [CarController::class, 'getCarServices']);
Route::get('/api/client-search', [ClientController::class, 'search']);
;
