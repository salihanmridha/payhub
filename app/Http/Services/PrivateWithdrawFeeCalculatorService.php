<?php

namespace App\Http\Services;

use App\Http\Contracts\CommonFeeCalculatorInterface;
use App\Traits\CurrencyConverter;


class PrivateWithdrawFeeCalculatorService extends CommonFeeCalculationQueryService implements CommonFeeCalculatorInterface
{
    use CurrencyConverter;

    /**
     * @param  array $fileElement
     * @param  array $freeLimitUsed
     * @return mixed|int|float
     */
    public function feeCalculate(array $fileElement, array $freeLimitUsed = null, array $crossRate = null): mixed
    {

      $clientPaymentType = $fileElement["client_type"] . "_" . $fileElement["payment_type"];
      $paymentRule = config('payhub.payment_rule')[$clientPaymentType];

      $getChargeAmount = 0.00;

      if ($crossRate && $crossRate["base"] && $crossRate["rate"]) {
        if ($fileElement["currency"] == $crossRate["base"]) {
          $getChargeAmount = ($freeLimitUsed["using_limit"] - $fileElement["amount"]) < 0 ? ($fileElement["amount"] - $freeLimitUsed["using_limit"]) : 0.00;
        }

        if ($fileElement["currency"] != $crossRate["base"]) {
          $amount = $fileElement["amount"] / $crossRate["rate"];
          $getChargeAmount = ($freeLimitUsed["using_limit"] - $amount) < 0 ? ($amount - $freeLimitUsed["using_limit"]) : 0.00;
          $getChargeAmount = $crossRate["rate"] * $getChargeAmount;
        }
      }


      return $this->calculation($getChargeAmount, $paymentRule["fee"], $fileElement["currency"]);

    }

}
