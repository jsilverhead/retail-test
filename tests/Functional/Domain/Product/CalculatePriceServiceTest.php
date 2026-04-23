<?php

namespace App\Tests\Functional\Domain\Product;

use App\Domain\Coupon\Enum\CodeTypeEnum;
use App\Domain\Product\Service\CalculatePriceService;
use App\Domain\Product\ValueObject\Price;
use App\Infrastructure\Exception\EntityNotFoundException;
use App\Tests\Builder\CouponBuilder;
use App\Tests\Builder\ProductBuilder;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * @internal
 */
#[CoversClass(CalculatePriceService::class)]
final class CalculatePriceServiceTest extends WebTestCase
{
    public function testNotSupportedCountryFail(): void
    {
        $service = $this->getContainer()->get(CalculatePriceService::class);
        $productBuilder = $this->getContainer()->get(ProductBuilder::class);
        $couponBuilder = $this->getContainer()->get(CouponBuilder::class);

        $product = $productBuilder->withPrice(new Price(euro: 100, cent: 0))->withName('Test Iphone')->build();

        $coupon = $couponBuilder->withType(CodeTypeEnum::PERCENTAGE)->withPercentage(6)->build();

        $this->expectException(BadRequestHttpException::class);
        $service->calculate(productId: $product->getId(), taxCode: 'RU123456789', couponCode: $coupon->getCode());
    }

    public function testNotUndefinedCouponFail(): void
    {
        $service = $this->getContainer()->get(CalculatePriceService::class);
        $productBuilder = $this->getContainer()->get(ProductBuilder::class);

        $product = $productBuilder->withPrice(new Price(euro: 100, cent: 0))->withName('Test Iphone')->build();

        $this->expectException(EntityNotFoundException::class);
        $service->calculate(productId: $product->getId(), taxCode: 'DE123456789', couponCode: 'SALE21');
    }

    public function testSuccess(): void
    {
        $service = $this->getContainer()->get(CalculatePriceService::class);
        $productBuilder = $this->getContainer()->get(ProductBuilder::class);
        $couponBuilder = $this->getContainer()->get(CouponBuilder::class);

        $product = $productBuilder->withPrice(new Price(euro: 100, cent: 0))->withName('Test Iphone')->build();
        $coupon = $couponBuilder->withType(CodeTypeEnum::PERCENTAGE)->withPercentage(6)->build();

        $price = $service->calculate(
            productId: $product->getId(),
            taxCode: 'GR123456789',
            couponCode: $coupon->getCode(),
        );

        self::assertSame(expected: 116, actual: $price->euro);
        self::assertSame(expected: 56, actual: $price->cent);
    }

    public function testUndefinedWareFail(): void
    {
        $service = $this->getContainer()->get(CalculatePriceService::class);
        $couponBuilder = $this->getContainer()->get(CouponBuilder::class);

        $coupon = $couponBuilder->withType(CodeTypeEnum::PERCENTAGE)->withPercentage(6)->build();

        $this->expectException(EntityNotFoundException::class);
        $service->calculate(productId: 5, taxCode: 'GR123456789', couponCode: $coupon->getCode());
    }
}
