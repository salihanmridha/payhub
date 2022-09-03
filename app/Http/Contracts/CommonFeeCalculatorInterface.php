<?php
namespace App\Http\Contracts;

interface CommonFeeCalculatorInterface
{
  public function feeCalculate(array $fileElement): mixed;
}
