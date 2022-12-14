<?php

namespace App\Http\Services;

use App\Http\Contracts\CommonFeeCalculatorInterface;


class BusinessDepositFeeCalculatorService extends CommonFeeCalculationQueryService implements
    CommonFeeCalculatorInterface
{

    /**
     * @param array $fileElement
     * @param array|null $freeLimitUsed
     * @param array|null $crossRate
     * @return mixed
     */
    public function feeCalculate(array $fileElement, array $freeLimitUsed = null, array $crossRate = null): mixed
    {
        $clientPaymentType = $fileElement["client_type"] . "_" . $fileElement["payment_type"];
        $paymentRule = config('payhub.payment_rule')[$clientPaymentType];

        return $this->calculation($fileElement["amount"], $paymentRule['fee'], $fileElement["currency"]);
    }

}
