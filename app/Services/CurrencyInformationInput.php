<?php

namespace App\Services;

class CurrencyInformationInput
{
    public function __construct(
       public readonly array $codes,
       public readonly array $numbers
    ){
    }
}