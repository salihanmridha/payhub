<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Client;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $clients = ["private", "business", "private", "private", "private"];

        for ($i=0; $i < count($clients); $i++) {
          Client::factory()->create([
            'type' => $clients[$i],
          ]);
        }
    }
}
