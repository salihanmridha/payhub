<?php

namespace App\Http\Services;

use App\Traits\CurrencyConverter;

class CommonFeeCalculationQueryService
{
    use CurrencyConverter;

    protected array $freeLimitUsed;
    protected array $crossRate;

    /**
     * @param array $fileElement
     * @param array $crossRate
     * @return array
     * @property array $freeLimitUsed
     */
    public function manageFreeFeeLimit(
        array $fileElement,
        array $crossRate
    ): array {
        $clientPaymentType = $fileElement["client_type"] . "_" . $fileElement["payment_type"];
        $paymentRule = config('payhub.payment_rule')[$clientPaymentType];

        $getFreeLimit = $this->getFreeLimit($paymentRule, $fileElement);

        $usingLimit = 0.00;
        if ($getFreeLimit > 0.00) {
            $usingLimit = $this->usingLimit($fileElement, $getFreeLimit, $crossRate);
            $this->setUsedLimits($fileElement, $usingLimit);
        }

        return [
            "using_limit" => $usingLimit,
            "used_limit" => $this->freeLimitUsed,
        ];
    }


    /**
     * @param array $paymentRule
     * @param array $fileElement
     * @return float
     */
    public function getFreeLimit(array $paymentRule, array $fileElement): float
    {
        $startEndWeekDay = startEndWeekDay($fileElement["payment_date"]);
        $dateRange = $startEndWeekDay["week_first_day"] . " " . $startEndWeekDay["week_last_day"];
        $clientId = $fileElement["client_id"];

        if (array_key_exists($clientId, $this->freeLimitUsed) &&
            array_key_exists($dateRange, $this->freeLimitUsed[$clientId])) {
            if (count($this->freeLimitUsed[$clientId][$dateRange]) == $paymentRule["free_of_charge"]) {
                return 0.00;
            }


            $totalAmount = array_sum($this->freeLimitUsed[$clientId][$dateRange]["amount"]);
            if ($totalAmount >= $paymentRule["free_of_charge_amount"]) {
                return 0.00;
            }

            return (float)($paymentRule["free_of_charge_amount"] - $totalAmount);
        }


        return (float)$paymentRule["free_of_charge_amount"];
    }

    /**
     * @param array $fileElement
     * @param float $getFreeLimit
     * @param array|null $crossRate
     * @return mixed
     */
    public function usingLimit(array $fileElement, float $getFreeLimit, array $crossRate = null): mixed
    {
        $usingLimit = 0.00;

        if ($crossRate && $crossRate["base"] && $crossRate["rate"]) {
            if ($fileElement["currency"] == $crossRate["base"]) {
                $usingLimit = ($getFreeLimit - $fileElement["amount"]) < 0 ? $getFreeLimit : $fileElement["amount"];
            }

            if ($fileElement["currency"] != $crossRate["base"]) {
                $amount = $fileElement["amount"] / $crossRate["rate"];
                $usingLimit = ($getFreeLimit - $amount) < 0 ? $getFreeLimit : $amount;
            }
        }

        return $usingLimit;
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
            $this->freeLimitUsed[$clientId][$dateRange]["payment_date"][] = $fileElement["payment_date"];
            $this->freeLimitUsed[$clientId][$dateRange]["amount"][] = $usingLimit;
        } elseif (array_key_exists($clientId, $this->freeLimitUsed)) {
            $this->freeLimitUsed[$clientId][$dateRange]["payment_date"] = [$fileElement["payment_date"]];
            $this->freeLimitUsed[$clientId][$dateRange]["amount"] = [$usingLimit];
        } else {
            $this->freeLimitUsed[$clientId][$dateRange]["payment_date"] = [$fileElement["payment_date"]];
            $this->freeLimitUsed[$clientId][$dateRange]["amount"] = [$usingLimit];
        }
    }

    /**
     * @param string
     * @return array
     */
    public function getCrossRate(string $currency): array
    {
        if (count($this->crossRate) == 0) {
            $this->crossRate = $this->getAllRates();
        }

        return ["base" => $this->crossRate["base"], "rate" => $this->crossRate['rates'][$currency]];
    }

    /**
     * @param float $amount
     * @param float $chargeBy
     * @param string $currency
     * @return string
     */
    public function calculation(float $amount, float $chargeBy, string $currency): string
    {
        $minorUnit = config('payhub.currency_minor_unit')[$currency];

        $commissionFee = ($amount * $chargeBy) / 100;

        if ($commissionFee <= number_format($commissionFee, $minorUnit)) {
            return number_format($commissionFee, $minorUnit, '.', '');
        }

        return number_format((round($commissionFee / 0.05)) * 0.05, (int)$minorUnit, '.', '');
    }
}
