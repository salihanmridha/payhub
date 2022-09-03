<?php

namespace App\Traits;

trait CurrencyConverter
{

    public function getAllRates(): mixed
    {
        $url = "https://developers.paysera.com/tasks/api/currency-exchange-rates";

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "Accept: application/json",
        );

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $resp = curl_exec($curl);
        curl_close($curl);
        $resp = json_decode($resp, true);
        return $resp;
    }

}
