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
    protected array $crossRate;

    public function __construct(FileParsingInterface $fileParsing)
    {
        $this->fileParsing = $fileParsing;

        $this->result = [];
        $this->freeLimitUsed = [];
        $this->crossRate = [];
    }

    /**
     * @throws Exception
     */
    public function execute(mixed $file): array
    {
        $fileParsing = $this->fileParsing->fileParser($file);

        foreach ($fileParsing as $fileElement) {

            $crossRate = null; $manageLimit = null;
            if ($fileElement["client_type"] == "private" && $fileElement["payment_type"] == "withdraw"){
                $crossRate = $this->getCrossRate($fileElement["currency"]);
                $manageLimit = $this->manageFreeFeeLimit($fileElement, $crossRate);
            }

            $fullClassName = 'App\\Http\\Services\\' . ucfirst($fileElement["payment_type"]) . "FeeCalculatorService";
            if (class_exists($fullClassName)) {
                $commissionFee = (new $fullClassName())->feeCalculate($fileElement, $manageLimit, $crossRate);
                $this->result[] = $commissionFee;
            } else {
                throw new Exception('CSV file has invalid payment type: ' . $fullClassName);
            }
        }

        return $this->result;
    }

}
