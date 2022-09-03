<?php

namespace App\Http\Services;

use App\Http\Contracts\FeeCalculatorInterface;
use App\Http\Contracts\FileParsingInterface;
use Exception;

class FeeCalculatorService extends CommonFeeCalculationQueryService implements FeeCalculatorInterface
{
    private FileParsingInterface $fileParsing;
    public array $result;
    protected array $freeLimitUsed;
    public array $crossRate;

    public function __construct(FileParsingInterface $fileParsing)
    {
        $this->fileParsing = $fileParsing;

        $this->result = [];
        $this->freeLimitUsed = [];
        $this->crossRate = [];
    }

    public function execute(mixed $file): array
    {
        $fileParsing = $this->fileParsing->fileParser($file);

        foreach ($fileParsing as $fileElement) {
            $fullClassName = 'App\\Http\\Services\\' . ucfirst($fileElement["payment_type"]) . "FeeCalculatorService";

            $manageLimit = $this->manageFreeFeeLimit($fileElement);
            $crossRate = $this->getCrossRate($fileElement["currency"]);

            if (class_exists($fullClassName)) {
                $commisionFee = (new $fullClassName())->feeCalculate($fileElement, $manageLimit, $crossRate);
                array_push($this->result, $commisionFee);
            } else {
                throw new Exception('CSV file has invalid payment type: ' . $fullClassName);
            }
        }

        return $this->result;
    }

}
