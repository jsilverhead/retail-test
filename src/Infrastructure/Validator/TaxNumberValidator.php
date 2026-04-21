<?php

namespace App\Infrastructure\Validator;

use App\Infrastructure\Calculator\Enum\CountriesWithTaxEnum;
use InvalidArgumentException;

final class TaxNumberValidator
{
    public function validateAndReturnCountry(string $taxNumber): CountriesWithTaxEnum
    {
        $country = $this->getCountry($taxNumber);

        $this->validateTaxNumber(taxNumber: $taxNumber, country: $country);

        return $country;
    }

    private function getCountry(string $taxNumber): CountriesWithTaxEnum
    {
        $validCodes = CountriesWithTaxEnum::getValidCodes();
        $country = null;

        foreach ($validCodes as $code) {
            if (str_contains($taxNumber, $code)) {
                $country = CountriesWithTaxEnum::from($code);

                break;
            }
        }

        if (null === $country) {
            throw new InvalidArgumentException('Country code not supported');
        }

        return $country;
    }

    private function validateTaxNumber(string $taxNumber, CountriesWithTaxEnum $country): void
    {
        $regexp = $country->getTaxRegexp();

        if (!preg_match($regexp, $taxNumber)) {
            throw new InvalidArgumentException('Invalid Tax number');
        }
    }
}
