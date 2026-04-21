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

        $expectedEuro = 101;
        $expectedCent = 15;

        $calculatedTotal = $calculator->calculate(ware: $ware, coupon: $coupon, country: $country);

        self::assertSame(expected: $expectedEuro, actual: $calculatedTotal->euro);
        self::assertSame(expected: $expectedCent, actual: $calculatedTotal->cent);
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

        $expectedEuro = 116;
        $expectedCents = 56;

        $calculatedTotal = $calculator->calculate(ware: $ware, coupon: $coupon, country: $country);

        self::assertSame(expected: $expectedEuro, actual: $calculatedTotal->euro);
        self::assertSame(expected: $expectedCents, actual: $calculatedTotal->cent);
    }

    public function testWithGreeceCountryWithoutCouponSuccess(): void
    {
        $calculator = $this->getContainer()->get(PriceCalculator::class);
        $wareBuilder = $this->getContainer()->get(WareBuilder::class);

        $country = CountriesWithTaxEnum::GR;
        $price = new Price(euro: 100, cent: 0);
        $ware = $wareBuilder->withPrice($price)->build();

        $expectedEuro = 124;
        $expectedCents = 0;

        $calculatedTotal = $calculator->calculate(ware: $ware, coupon: null, country: $country);
        self::assertSame(expected: $expectedEuro, actual: $calculatedTotal->euro);
        self::assertSame(expected: $expectedCents, actual: $calculatedTotal->cent);
    }
}
