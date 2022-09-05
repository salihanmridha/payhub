<?php

namespace App\Http\Services;

use App\Http\Contracts\CommonFeeCalculatorInterface;
use Exception;

class WithdrawFeeCalculatorService implements CommonFeeCalculatorInterface
{
    /**
     * @param array $fileElement
     * @param array|null $freeLimitUsed
     * @param array|null $crossRate
     * @return mixed
     * @throws Exception
     */
    public function feeCalculate(array $fileElement, array $freeLimitUsed = null, array $crossRate = null): mixed
    {
        $fullClassName = 'App\\Http\\Services\\' . ucfirst($fileElement["client_type"]) . ucfirst(
                $fileElement["payment_type"]
            ) . "FeeCalculatorService";

        if (class_exists($fullClassName)) {
            return (new $fullClassName())->feeCalculate($fileElement, $freeLimitUsed, $crossRate);
        } else {
            throw new Exception('CSV file has invalid client with payment type: ' . $fullClassName);
        }
    }

}
