<?php

namespace App\Http\Contracts;

interface FeeCalculatorInterface
{
    public function execute(mixed $file): array;
}
