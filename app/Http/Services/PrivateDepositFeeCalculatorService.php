<?php

namespace App\Http\Services;

use App\Http\Contracts\CommonFeeCalculatorInterface;
use App\Traits\CurrencyConverter;


class PrivateDepositFeeCalculatorService extends CommonFeeCalculationQueryService implements CommonFeeCalculatorInterface
{
    use CurrencyConverter;

    /**
     * @param  array $fileElement
     * @return mixed|int|float
     */
    public function feeCalculate(array $fileElement): mixed
    {
      $paymentRule = $this->getPaymentRuleByPaymentClientType($fileElement["payment_type"], $fileElement["client_type"]);

      return $this->calculation($fileElement["amount"], $paymentRule->fee, $fileElement["currency"]);
    }

}
