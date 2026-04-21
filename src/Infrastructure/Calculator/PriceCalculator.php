<?php

namespace App\Infrastructure\Calculator;

use App\Domain\Coupon\Coupon;
use App\Domain\Coupon\Enum\CodeTypeEnum;
use App\Domain\Ware\ValueObject\Price;
use App\Domain\Ware\Ware;
use App\Infrastructure\Calculator\Enum\CountriesWithTaxEnum;
use Exception;
use LogicException;

final class PriceCalculator
{
    public function calculate(Ware $ware, ?Coupon $coupon, CountriesWithTaxEnum $country): Price
    {
        $totalCents = $ware->getPrice()->toCents();

        if (null !== $coupon) {
            $totalCents = $this->applyCoupon(cents: $totalCents, coupon: $coupon);
        }

        $totalCents = $this->addTaxRate(cents: $totalCents, county: $country);

        return Price::fromCents($totalCents);
    }

    private function addTaxRate(int $cents, CountriesWithTaxEnum $county): int
    {
        $rate = match ($county) {
            CountriesWithTaxEnum::DE => 0.19,
            CountriesWithTaxEnum::IT => 0.22,
            CountriesWithTaxEnum::FR => 0.2,
            CountriesWithTaxEnum::GR => 0.24,
            default => throw new Exception('Unsupported country'),
        };

        $centsFloat = (float) $cents;

        return (int) round($centsFloat * (1.0 + $rate));
    }

    private function applyCoupon(int $cents, Coupon $coupon): int
    {
        if (CodeTypeEnum::FIXED_VALUE === $coupon->getType()) {
            if (null === $coupon->getFixedValue()) {
                throw new LogicException('Fixed value coupon should contain fixed value');
            }

            $fixedValue = $coupon->getFixedValue();

            return $cents - $fixedValue * 100;
        }

        if (CodeTypeEnum::PERCENTAGE === $coupon->getType()) {
            if (null === $coupon->getPercentage()) {
                throw new LogicException('Percentage coupon should contain percentage');
            }

            $percentage = (float) $coupon->getPercentage();
            $centsFloat = (float) $cents;

            return (int) round($centsFloat * (1.0 - $percentage / 100.0));
        }

        throw new LogicException('Unsupported coupon');
    }
}
