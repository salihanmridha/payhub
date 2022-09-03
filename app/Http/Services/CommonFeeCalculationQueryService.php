<?php

namespace App\Http\Services;

use App\Traits\CurrencyConverter;

class CommonFeeCalculationQueryService
{
    use CurrencyConverter;
    /**
     * @param  array       $paymentRule
     * @param  array       $fileElement
     * @param  array       $freeLimitUsed
     * @return float
     */
    public function getFreeLimit(array $paymentRule, array $fileElement, array $freeLimitUsed): float
    {
      $startEndWeekDay = startEndWeekDay($fileElement["payment_date"]);
      $dateRange = $startEndWeekDay["week_first_day"] . " " . $startEndWeekDay["week_last_day"];
      $clientId = $fileElement["client_id"];

      if (array_key_exists($clientId, $freeLimitUsed) &&
          array_key_exists($dateRange, $freeLimitUsed[$clientId])) {

          if (count($freeLimitUsed[$clientId][$dateRange]) == $paymentRule["free_of_charge"]) {
            return (float)0.00;
          }


          $totalAmount = array_sum($freeLimitUsed[$clientId][$dateRange]["amount"]);
          if ($totalAmount >= $paymentRule["free_of_charge_amount"]) {
            return (float)0.00;
          }

          if ($totalAmount < $paymentRule["free_of_charge_amount"]) {
            return (float) ($paymentRule["free_of_charge_amount"] - $totalAmount);
          }
      }


      return (float)$paymentRule["free_of_charge_amount"];
    }

    /**
     * @param  array  $fileElement
     * @param  float  $getFreeLimit
     * @param  array  $freeLimitUsed
     * @return float|int|mixed
     */
    public function usingLimit(array $fileElement, float $getFreeLimit, array $freeLimitUsed): mixed
    {
      $usingLimit = 0.00;

      if ($fileElement["currency"] == $this->getCrossRate($fileElement["currency"])["base"]) {
        $usingLimit = ($getFreeLimit - $fileElement["amount"]) < 0 ? $getFreeLimit : $fileElement["amount"];
      }

      if ($fileElement["currency"] != $this->getCrossRate($fileElement["currency"])["base"]) {
        $amount = $fileElement["amount"] / $this->getCrossRate($fileElement["currency"])["rate"];
        $usingLimit = ($getFreeLimit - $amount) < 0 ? $getFreeLimit : $amount;
      }

      return $usingLimit;

    }

    /**
     * @param  array $fileElement
     * @property array $freeLimitUsed
     * @return array
     */
    public function manageFreeFeeLimit(array $fileElement): array
    {
      $clientPaymentType = $fileElement["client_type"] . "_" . $fileElement["payment_type"];
      $paymentRule = config('payhub.payment_rule')[$clientPaymentType];
      $getFreeLimit = $this->getFreeLimit((array)$paymentRule, $fileElement, $this->freeLimitUsed);

      $usingLimit = 0.00;
      if ($getFreeLimit > 0.00) {
        $usingLimit =  $this->usingLimit($fileElement, $getFreeLimit, $this->freeLimitUsed);
        $this->setUsedLimits($fileElement, $usingLimit);
      }

      return [
        "using_limit" => $usingLimit,
        "used_limit" => $this->freeLimitUsed,
      ];
    }

    /**
     * @param array $fileElement
     * @param mixed|int|float $usingLimit
     * @return void
     */
    public function setUsedLimits(array $fileElement, mixed $usingLimit): void
    {
      $startEndWeekDay = startEndWeekDay($fileElement["payment_date"]);
      $dateRange = $startEndWeekDay["week_first_day"] . " " . $startEndWeekDay["week_last_day"];
      $clientId = $fileElement["client_id"];

      if (array_key_exists($clientId, $this->freeLimitUsed) &&
          array_key_exists($dateRange, $this->freeLimitUsed[$clientId])) {

            array_push($this->freeLimitUsed[$clientId][$dateRange]["payment_date"], $fileElement["payment_date"]);
            array_push($this->freeLimitUsed[$clientId][$dateRange]["amount"], $usingLimit);

      } elseif (array_key_exists($clientId, $this->freeLimitUsed)) {

        $this->freeLimitUsed[$clientId][$dateRange]["payment_date"] = [$fileElement["payment_date"]];
        $this->freeLimitUsed[$clientId][$dateRange]["amount"] = [$usingLimit];

      } else {
        $this->freeLimitUsed[$clientId][$dateRange]["payment_date"] = [$fileElement["payment_date"]];
        $this->freeLimitUsed[$clientId][$dateRange]["amount"] = [$usingLimit];
      }
    }

    /**
     * @param  float  $amount
     * @param  float  $chargeBy
     * @param  string $currency
     * @return mixed|int|float|null
     */
    public function calculation(float $amount, float $chargeBy, string $currency): mixed
    {

      $minorUnit = config('payhub.currency_minor_unit')[$currency];

      $commisionFee = ($amount * $chargeBy) / 100;

      if ($commisionFee <= number_format($commisionFee, $minorUnit )) {
        return number_format($commisionFee, $minorUnit, '.', '');
      }

      return number_format((round($commisionFee / 0.05, 0)) * 0.05, $minorUnit, '.', '');

    }

    /**
     * @param  string
     * @return array
     */
    public function getCrossRate(string $currency): array
    {
      if (count($this->crossRate) == 0) {
        $this->crossRate = $this->getAllRates();
      }

      return ["base" => $this->crossRate["base"], "rate" => $this->crossRate['rates'][$currency]];
    }
}
