<?php

return [

    /*
    |--------------------------------------------------------------------------
    | System Application Defined Variables
    |--------------------------------------------------------------------------
    |
    | This is a set of variables that are made specific to this application
    | that are better placed here rather than in .env file.
    | Import Config to your class and use Config::get(payhub.your_key) to get the values.
    |
     */

     //payment rules
    'payment_rule' => [
      "private_withdraw" => [
        "fee" => 0.3,
        "free_of_charge_amount" => 1000.00,
        "free_of_charge" => 3,
      ],
      "business_withdraw" => [
        "fee" => 0.5,
        "free_of_charge_amount" => 0.00,
        "free_of_charge" => 0,
      ],
      "private_deposit" => [
        "fee" => 0.03,
        "free_of_charge_amount" => 0.00,
        "free_of_charge" => 0,
      ],
      "business_deposit" => [
        "fee" => 0.03,
        "free_of_charge_amount" => 0.00,
        "free_of_charge" => 0,
      ],
    ],
    //currency minor unit
    'currency_minor_unit' => [
      "EUR" => 2,
      "USD" => 2,
      "JPY" => 0,
    ],


];
