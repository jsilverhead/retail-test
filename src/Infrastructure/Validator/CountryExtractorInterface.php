<?php

namespace App\Infrastructure\Validator;

use App\Infrastructure\Calculator\Enum\CountriesWithTaxEnum;

interface CountryExtractorInterface
{
    public function extractCountry(string $taxNumber): CountriesWithTaxEnum;
}
