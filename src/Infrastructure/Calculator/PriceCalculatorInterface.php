<?php

namespace App\Infrastructure\Calculator;

use App\Domain\Coupon\Coupon;
use App\Domain\Ware\ValueObject\Price;
use App\Domain\Ware\Ware;
use App\Infrastructure\Calculator\Enum\CountriesWithTaxEnum;

interface PriceCalculatorInterface
{
    public function calculate(Ware $ware, ?Coupon $coupon, CountriesWithTaxEnum $country): Price;
}
