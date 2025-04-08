<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
 use App\Models\Car;

class ClientController extends Controller
{
    public function index()
    {
        return Client::select('id', 'name', 'idcard')->get();
    }

    public function cars($id)
    {
        $client = Client::with('cars')->findOrFail($id);
        return response()->json($client->cars);
    }

}
