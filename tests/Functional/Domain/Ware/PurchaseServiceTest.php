<?php

namespace App\Tests\Functional\Domain\Ware;

use App\Domain\Coupon\Enum\CodeTypeEnum;
use App\Domain\Product\Enum\PaymentProcessorsEnum;
use App\Domain\Product\Exception\ProcessPaymentFailedException;
use App\Domain\Product\Service\PurchaseService;
use App\Domain\Product\ValueObject\Price;
use App\Tests\Builder\CouponBuilder;
use App\Tests\Builder\ProductBuilder;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 */
#[CoversClass(PurchaseService::class)]
final class PurchaseServiceTest extends WebTestCase
{
    public function testPaypalTooHighPriceSuccess(): void
    {
        $service = $this->getContainer()->get(PurchaseService::class);
        $productBuilder = $this->getContainer()->get(ProductBuilder::class);
        $couponBuilder = $this->getContainer()->get(CouponBuilder::class);

        $product = $productBuilder->withName('Iphone')->withPrice(new Price(euro: 10000, cent: 0))->build();
        $coupon = $couponBuilder->withType(CodeTypeEnum::PERCENTAGE)->withPercentage(6)->build();

        $this->expectException(ProcessPaymentFailedException::class);
        $service->purchase(
            productId: $product->getId(),
            taxCode: 'GR123456789',
            couponCode: $coupon->getCode(),
            processor: PaymentProcessorsEnum::PAYPAL,
        );
    }

    public function testStripeTooLowPriceSuccess(): void
    {
        $service = $this->getContainer()->get(PurchaseService::class);
        $productBuilder = $this->getContainer()->get(ProductBuilder::class);
        $couponBuilder = $this->getContainer()->get(CouponBuilder::class);

        $product = $productBuilder->withName('Iphone')->withPrice(new Price(euro: 10, cent: 0))->build();
        $coupon = $couponBuilder->withType(CodeTypeEnum::PERCENTAGE)->withPercentage(6)->build();

        $this->expectException(ProcessPaymentFailedException::class);
        $service->purchase(
            productId: $product->getId(),
            taxCode: 'GR123456789',
            couponCode: $coupon->getCode(),
            processor: PaymentProcessorsEnum::STRIPE,
        );
    }

    public function testWithPaypalSuccess(): void
    {
        $service = $this->getContainer()->get(PurchaseService::class);
        $productBuilder = $this->getContainer()->get(ProductBuilder::class);
        $couponBuilder = $this->getContainer()->get(CouponBuilder::class);

        $product = $productBuilder->withName('Iphone')->withPrice(new Price(euro: 100, cent: 0))->build();
        $coupon = $couponBuilder->withType(CodeTypeEnum::PERCENTAGE)->withPercentage(6)->build();

        $service->purchase(
            productId: $product->getId(),
            taxCode: 'GR123456789',
            couponCode: $coupon->getCode(),
            processor: PaymentProcessorsEnum::PAYPAL,
        );
        $this->expectNotToPerformAssertions();
    }

    public function testWithStripeSuccess(): void
    {
        $service = $this->getContainer()->get(PurchaseService::class);
        $productBuilder = $this->getContainer()->get(ProductBuilder::class);
        $couponBuilder = $this->getContainer()->get(CouponBuilder::class);

        $product = $productBuilder->withName('Iphone')->withPrice(new Price(euro: 100, cent: 0))->build();
        $coupon = $couponBuilder->withType(CodeTypeEnum::PERCENTAGE)->withPercentage(6)->build();

        $service->purchase(
            productId: $product->getId(),
            taxCode: 'GR123456789',
            couponCode: $coupon->getCode(),
            processor: PaymentProcessorsEnum::STRIPE,
        );
        $this->expectNotToPerformAssertions();
    }
}
