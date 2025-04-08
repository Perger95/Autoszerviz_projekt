<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CarsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Ellenőrizzük, hogy üres-e a cars tábla
        if (DB::table('cars')->count() === 0) {
            // Ha üres, beolvassuk a JSON fájlt
            $json = File::get(database_path('seeders/data/cars.json'));
            $cars = json_decode($json, true);

            // Az adatok beszúrása a cars táblába
            DB::table('cars')->insert(array_map(function ($car) {
                return [
                    'client_id' => $car['client_id'],
                    'car_id' => $car['car_id'],
                    'type' => $car['type'],
                    'registered' => $car['registered'],
                    'ownbrand' => $car['ownbrand'],
                    'accident' => $car['accident'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }, $cars));

            $this->command->info('Cars table seeded!');
        } else {
            $this->command->info('Cars table not empty, skipping.');
        }
    }
}
