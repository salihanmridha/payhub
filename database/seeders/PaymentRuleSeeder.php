<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PaymentRule;

class PaymentRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      PaymentRule::factory()->create([
        'payment_type'          => "deposit",
        'client_type'           => "private",
        'fee'                   => 0.03,
        'free_of_charge'        => 0,
        'free_of_charge_amount' => 0,
      ]);

      PaymentRule::factory()->create([
        'payment_type'          => "deposit",
        'client_type'           => "business",
        'fee'                   => 0.03,
        'free_of_charge'        => 0,
        'free_of_charge_amount' => 0,
      ]);

      //withdraw
      PaymentRule::factory()->create([
        'payment_type'          => "withdraw",
        'client_type'           => "private",
        'fee'                   => 0.3,
        'free_of_charge'        => 3,
        'free_of_charge_amount' => 1000.00,
      ]);

      PaymentRule::factory()->create([
        'payment_type'          => "withdraw",
        'client_type'           => "business",
        'fee'                   => 0.5,
        'free_of_charge'        => 0,
        'free_of_charge_amount' => 0,
      ]);
    }
}
