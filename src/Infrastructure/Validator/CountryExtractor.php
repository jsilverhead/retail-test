<?php

namespace App\Infrastructure\Validator;

use App\Infrastructure\Calculator\Enum\CountriesWithTaxEnum;
use InvalidArgumentException;

final class CountryExtractor
{
    public function extractCountry(string $taxNumber): CountriesWithTaxEnum
    {
        foreach (CountriesWithTaxEnum::getValidCodes() as $code) {
            if (str_contains($taxNumber, $code)) {
                return CountriesWithTaxEnum::from($code);
            }
        }

        throw new InvalidArgumentException('Country code not supported');
    }
}
