<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Http\Contracts\FeeCalculatorInterface;

class PaymentController extends Controller
{
    private FeeCalculatorInterface $feeCalculator;

    public function __construct(FeeCalculatorInterface $feeCalculator)
    {
      $this->feeCalculator = $feeCalculator;
    }

    /**
     * @param  StorePaymentRequest $request
     * @return void
     */
    public function store(StorePaymentRequest $request): void
    {
      $feeCalculation =  $this->feeCalculator->execute($request->file("file"));

      for ($i=0; $i < count($feeCalculation); $i++) {
        echo $feeCalculation[$i] . "<br>";
      }
    }
}
