<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;

class ClientController extends Controller
{
    public function index()
    {
        return Client::select('id', 'name', 'idcard')->get();
    }

    public function cars($id)
    {
        $client = Client::with('cars')->findOrFail($id);

        $cars = $client->cars->map(function ($car) {
            $lastService = \App\Models\Service::where('car_id', $car->car_id)
                ->where('client_id', $car->client_id)
                ->orderByDesc('lognumber')
                ->first();

            $car->last_event = $lastService?->event ?? null;
            $car->last_event_time = $lastService && $lastService->event === 'regisztralt'
                ? $car->registered
                : ($lastService?->event_time ?? $lastService?->eventtime ?? null);

            return $car;
        });

        return response()->json($cars->values());
    }



    public function search(Request $request)
    {
        $request->validate([
            'name' => 'nullable|string',
            'idcard' => 'nullable|alpha_num',
        ], [
            'idcard.alpha_num' => 'Az okmányazonosító csak betűket és számokat tartalmazhat!',
        ]);

        $name = $request->input('name');
        $idcard = $request->input('idcard');

        // Validálások
        if (!$name && !$idcard) {
            return response()->json(['message' => 'Legalább az egyik mezőt ki kell tölteni!'], 422);
        }
        if ($name && $idcard) {
            return response()->json(['message' => 'Csak az egyik mezőt töltsd ki!'], 422);
        }

        // Keresés okmányazonosító szerint, pontos egyezés
        // További validálások
        if ($idcard) {
            $client = Client::where('idcard', $idcard)->withCount(['cars', 'services'])->first();
            if (!$client) {
                return response()->json(['message' => 'Nem található ilyen ügyfél!'], 404);
            }
            return response()->json([
                'id' => $client->id,
                'name' => $client->name,
                'idcard' => $client->idcard,
                'car_count' => $client->cars_count ?? 0,
                'service_count' => $client->services_count ?? 0,
            ]);
        }

        // Keresés névrészlet alapján
        $clients = Client::where('name', 'like', '%' . $name . '%')->withCount(['cars', 'services'])->get();
        if ($clients->count() === 0) {
            return response()->json(['message' => 'Nem található ilyen ügyfél!'], 404);
        }
        if ($clients->count() > 1) {
            return response()->json(['message' => 'Több találat van, pontosítsd a keresést!'], 422);
        }
        $client = $clients->first();

        return response()->json([
            'id' => $client->id,
            'name' => $client->name,
            'idcard' => $client->idcard,
            'car_count' => $client->cars_count ?? 0,
            'service_count' => $client->services_count ?? 0,
        ]);
    }

}
