<?php

namespace App\Tests\Functional\Infrastructure\Calculator;

use App\Domain\Coupon\Enum\CodeTypeEnum;
use App\Domain\Ware\ValueObject\Price;
use App\Infrastructure\Calculator\Enum\CountriesWithTaxEnum;
use App\Infrastructure\Calculator\PriceCalculator;
use App\Tests\Builder\CouponBuilder;
use App\Tests\Builder\WareBuilder;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 */
#[CoversClass(PriceCalculator::class)]
final class PriceCalculatorTest extends WebTestCase
{
    public function testWithDeutschCountryAndFixedCouponSuccess(): void
    {
        $calculator = $this->getContainer()->get(PriceCalculator::class);
        $couponBuilder = $this->getContainer()->get(CouponBuilder::class);
        $wareBuilder = $this->getContainer()->get(WareBuilder::class);

        $country = CountriesWithTaxEnum::DE;
        $coupon = $couponBuilder->withType(CodeTypeEnum::FIXED_VALUE)->withFixedValue(15)->build();
        $price = new Price(euro: 100, cent: 0);
        $ware = $wareBuilder->withPrice($price)->build();

        /** @psalm-var int $fixedValue */
        $fixedValue = $coupon->getFixedValue();
        $cents = $price->toCents();
        $centsWithCoupon = $cents - $fixedValue * 100;
        $centsWithTax = (int) ((float) $centsWithCoupon + (float) $centsWithCoupon * 0.19);
        $total = Price::fromCents($centsWithTax);
        $calculatedTotal = $calculator->calculate(ware: $ware, coupon: $coupon, country: $country);

        self::assertSame(expected: $total->euro, actual: $calculatedTotal->euro);
    }

    public function testWithGreeceCountryAndPercentageCouponSuccess(): void
    {
        $calculator = $this->getContainer()->get(PriceCalculator::class);
        $couponBuilder = $this->getContainer()->get(CouponBuilder::class);
        $wareBuilder = $this->getContainer()->get(WareBuilder::class);

        $country = CountriesWithTaxEnum::GR;
        $coupon = $couponBuilder->withType(CodeTypeEnum::PERCENTAGE)->withPercentage(6)->build();
        $price = new Price(euro: 100, cent: 0);
        $ware = $wareBuilder->withPrice($price)->build();

        /** @psalm-var int $percents */
        $percents = $coupon->getPercentage();
        $floatPercents = (float) $percents;

        $cents = (float) $ware->getPrice()->toCents();
        $centsWithCoupon = (int) round($cents * (1.0 - $floatPercents / 100.0));
        $centsWithTax = (int) round((float) $centsWithCoupon * (1.0 + 0.24));
        $expectedTotal = Price::fromCents($centsWithTax);

        $calculatedTotal = $calculator->calculate(ware: $ware, coupon: $coupon, country: $country);

        self::assertSame(expected: $expectedTotal->euro, actual: $calculatedTotal->euro);
        self::assertSame(expected: $expectedTotal->cent, actual: $calculatedTotal->cent);
    }

    public function testWithGreeceCountryWithoutCouponSuccess(): void
    {
        $calculator = $this->getContainer()->get(PriceCalculator::class);
        $wareBuilder = $this->getContainer()->get(WareBuilder::class);

        $country = CountriesWithTaxEnum::GR;
        $price = new Price(euro: 100, cent: 0);
        $ware = $wareBuilder->withPrice($price)->build();

        $cents = (float) $ware->getPrice()->toCents();
        $centsWithTax = (int) round($cents * (1.0 + 0.24));
        $expectedTotal = Price::fromCents($centsWithTax);

        $calculatedTotal = $calculator->calculate(ware: $ware, coupon: null, country: $country);
        self::assertSame(expected: $expectedTotal->euro, actual: $calculatedTotal->euro);
    }
}
