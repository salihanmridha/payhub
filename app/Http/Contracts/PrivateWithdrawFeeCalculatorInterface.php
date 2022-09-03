<?php
namespace App\Http\Contracts;

interface PrivateWithdrawFeeCalculatorInterface
{
  public function storeFreeOfChargePayment(array $fileElement, float $getFreeLimit): void;
}
