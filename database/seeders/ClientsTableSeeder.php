<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (DB::table('clients')->count() === 0) {
            $json = File::get(database_path('seeders/data/clients.json'));
            $clients = json_decode($json, true);

            DB::table('clients')->insert(array_map(function ($client) {
                return [
                    'id' => $client['id'],
                    'name' => $client['name'],
                    'idcard' => $client['idcard'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }, $clients));

            $this->command->info('Clients table seeded!');
        } else {
            $this->command->info('Clients table not empty, skipping.');
        }
    }
}
