<?php

namespace App\Infrastructure\Calculator;

use App\Domain\Coupon\Coupon;
use App\Domain\Product\Product;
use App\Domain\Product\ValueObject\Price;
use App\Infrastructure\Calculator\Enum\CountriesWithTaxEnum;

interface PriceCalculatorInterface
{
    public function calculate(Product $product, ?Coupon $coupon, CountriesWithTaxEnum $country): Price;
}
