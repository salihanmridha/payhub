<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use App\Models\PaymentRule;
use App\Models\Client;


class FeeCalculationTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    public function setUp() : void
    {
        parent::setUp();

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

        $clients = ["private", "business", "private", "private", "private"];

        for ($i=0; $i < count($clients); $i++) {
          Client::factory()->create([
            'type' => $clients[$i],
          ]);
        }

        $this->user = User::factory()->create();
        $response = $this->post('/login', [
            'email' => $this->user->email,
            'password' => 'password',
        ]);
    }

    public function test_logged_in_users_can_upload_and_calculate_fee()
    {
        $filePath = base_path('tests/Feature/input.csv');

        $name = "input".'.csv';
        $path = sys_get_temp_dir().'/'.$name;
        copy($filePath, $path);
        $file = new UploadedFile($path, $name, filesize($path), null, true, true);
        $attributes = [
            'file'           => $file,
        ];

        $response = $this->post('/payment-store', $attributes)
                         ->assertStatus(200);

        $this->expectOutputString('0.60<br>3.00<br>0.00<br>0.06<br>1.50<br>0.00<br>0.69<br>0.30<br>0.30<br>3.00<br>0.00<br>0.00<br>8,607.40<br>');
    }
}
