<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CurrencyMinorUnit;

class CurrencyMinorUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      CurrencyMinorUnit::factory()->create([
        'currency'          => "EUR",
        'minor_unit'          => 2,
      ]);

      CurrencyMinorUnit::factory()->create([
        'currency'          => "USD",
        'minor_unit'          => 2,
      ]);

      CurrencyMinorUnit::factory()->create([
        'currency'          => "JPY",
        'minor_unit'          => 0,
      ]);
    }
}
