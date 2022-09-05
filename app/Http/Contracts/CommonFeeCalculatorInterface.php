<?php

namespace App\Http\Contracts;

interface CommonFeeCalculatorInterface
{
    public function feeCalculate(array $fileElement, array $freeLimitUsed = null, array $crossRate = null): mixed;
}
