<?php

namespace App\Services;

use App\Repository\CurrencyDataSource;

class CurrencyInformationService
{
    public function __construct(
        public readonly CurrencyDataSource $currencyDataSource
    ){
    }

    public function search(array $requestData): CurrencyInformationOutput
    {
        $codes = [];
        $numbers = [];

        if (!empty($requestData['code'])) {
            $codes[] = $requestData['code'];
        }

        if (!empty($requestData['code_list'])) {
            $codes = $requestData['code_list'];
        }

        if (!empty($requestData['number'])) {
            $numbers[] = $requestData['number'];
        }

        if (!empty($requestData['number_lists'])) {
            $numbers = $requestData['number_lists'];
        }

        $input = new CurrencyInformationInput($codes, $numbers);
        return $this->currencyDataSource->find($input);
    }
}