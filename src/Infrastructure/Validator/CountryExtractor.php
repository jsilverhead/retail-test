<?php

namespace App\Infrastructure\Validator;

use App\Domain\Ware\Service\CountryExtractorInterface;
use App\Infrastructure\Calculator\Enum\CountriesWithTaxEnum;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class CountryExtractor implements CountryExtractorInterface
{
    public function extractCountry(string $taxNumber): CountriesWithTaxEnum
    {
        foreach (CountriesWithTaxEnum::getValidCodes() as $code) {
            if (str_contains($taxNumber, $code)) {
                return CountriesWithTaxEnum::from($code);
            }
        }

        throw new BadRequestHttpException('Country code not supported');
    }
}
