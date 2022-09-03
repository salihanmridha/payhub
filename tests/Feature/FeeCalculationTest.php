<?php

namespace Tests\Feature;

use App\Providers\RouteServiceProvider;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;


class FeeCalculationTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function test_users_can_upload_and_calculate_fee()
    {
        $filePath = base_path('tests/Feature/input.csv');

        $name = "input" . '.csv';
        $path = sys_get_temp_dir() . '/' . $name;
        copy($filePath, $path);
        $file = new UploadedFile($path, $name, filesize($path), null, true, true);
        $attributes = [
            'file' => $file,
        ];

        $response = $this->post('/payment-store', $attributes)
            ->assertStatus(200);

        $this->expectOutputString(
            '0.60<br>3.00<br>0.00<br>0.06<br>1.50<br>0<br>0.69<br>0.30<br>0.30<br>3.00<br>0.00<br>0.00<br>8607<br>'
        );
    }
}
