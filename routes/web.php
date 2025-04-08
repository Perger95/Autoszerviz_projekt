<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ClientController;

Route::get('/', function () {
    return view('welcome');
});

// Ügyfél lista API (Vue komponenshez)
Route::get('/api/clients', [ClientController::class, 'index']);
