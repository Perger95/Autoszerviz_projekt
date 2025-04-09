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
        $validated = $request->validate([
            'name' => 'nullable|required_without:idcard|string',
            'idcard' => 'nullable|required_without:name|alpha_num',
        ], [
            'name.required_without' => 'Legalább az egyik mezőt ki kell tölteni!',
            'idcard.required_without' => 'Legalább az egyik mezőt ki kell tölteni!',
            'idcard.alpha_num' => 'Az okmányazonosító csak betűket és számokat tartalmazhat!',
        ]);

        $clients = Client::query()
            ->when($validated['name'] ?? null, fn($q) => $q->where('name', 'like', '%' . $validated['name'] . '%'))
            ->when($validated['idcard'] ?? null, fn($q) => $q->where('idcard', $validated['idcard']))
            ->withCount(['cars', 'services'])
            ->get();

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
