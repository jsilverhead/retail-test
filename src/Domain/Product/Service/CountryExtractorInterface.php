<?php

namespace App\Domain\Product\Service;

use App\Infrastructure\Calculator\Enum\CountriesWithTaxEnum;

interface CountryExtractorInterface
{
    public function extractCountry(string $taxNumber): CountriesWithTaxEnum;
}
