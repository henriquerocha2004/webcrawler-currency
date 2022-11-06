<?php

namespace App\Repository;

use App\Services\CurrencyInformationInput;
use App\Services\CurrencyInformationOutput;

interface CurrencyDataSource
{
    public function find(CurrencyInformationInput $input): CurrencyInformationOutput;
}