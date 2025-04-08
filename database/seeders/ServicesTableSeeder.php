<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ServicesTableSeeder extends Seeder
{
    public function run()
    {
        if (DB::table('services')->count() === 0) {
            $json = File::get(database_path('seeders/data/services.json'));
            $services = json_decode($json, true);

            DB::table('services')->insert(array_map(function ($service) {
                return [
                    'client_id' => $service['client_id'],
                    'car_id' => $service['car_id'],
                    'lognumber' => $service['lognumber'],
                    'event' => $service['event'],
                    'eventtime' => $service['eventtime'] ?? null,
                    'document_id' => $service['document_id'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }, $services));

            $this->command->info('Services table seeded!');
        } else {
            $this->command->info('Services table not empty, skipping.');
        }
    }
}
