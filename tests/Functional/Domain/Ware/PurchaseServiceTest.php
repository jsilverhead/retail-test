<?php

namespace App\Tests\Functional\Domain\Ware;

use App\Domain\Coupon\Enum\CodeTypeEnum;
use App\Domain\Ware\Enum\PaymentProcessorsEnum;
use App\Domain\Ware\Exception\ProcessPaymentFailedException;
use App\Domain\Ware\Service\PurchaseService;
use App\Domain\Ware\ValueObject\Price;
use App\Tests\Builder\CouponBuilder;
use App\Tests\Builder\WareBuilder;
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
        $wareBuilder = $this->getContainer()->get(WareBuilder::class);
        $couponBuilder = $this->getContainer()->get(CouponBuilder::class);

        $ware = $wareBuilder->withName('Iphone')->withPrice(new Price(euro: 10000, cent: 0))->build();
        $coupon = $couponBuilder->withType(CodeTypeEnum::PERCENTAGE)->withPercentage(6)->build();

        $this->expectException(ProcessPaymentFailedException::class);
        $service->purchase(
            productId: $ware->getId(),
            taxCode: 'GR123456789',
            couponCode: $coupon->getCode(),
            processor: PaymentProcessorsEnum::PAYPAL,
        );
    }

    public function testStripeTooLowPriceSuccess(): void
    {
        $service = $this->getContainer()->get(PurchaseService::class);
        $wareBuilder = $this->getContainer()->get(WareBuilder::class);
        $couponBuilder = $this->getContainer()->get(CouponBuilder::class);

        $ware = $wareBuilder->withName('Iphone')->withPrice(new Price(euro: 10, cent: 0))->build();
        $coupon = $couponBuilder->withType(CodeTypeEnum::PERCENTAGE)->withPercentage(6)->build();

        $this->expectException(ProcessPaymentFailedException::class);
        $service->purchase(
            productId: $ware->getId(),
            taxCode: 'GR123456789',
            couponCode: $coupon->getCode(),
            processor: PaymentProcessorsEnum::STRIPE,
        );
    }

    public function testWithPaypalSuccess(): void
    {
        $service = $this->getContainer()->get(PurchaseService::class);
        $wareBuilder = $this->getContainer()->get(WareBuilder::class);
        $couponBuilder = $this->getContainer()->get(CouponBuilder::class);

        $ware = $wareBuilder->withName('Iphone')->withPrice(new Price(euro: 100, cent: 0))->build();
        $coupon = $couponBuilder->withType(CodeTypeEnum::PERCENTAGE)->withPercentage(6)->build();

        $service->purchase(
            productId: $ware->getId(),
            taxCode: 'GR123456789',
            couponCode: $coupon->getCode(),
            processor: PaymentProcessorsEnum::PAYPAL,
        );
        $this->expectNotToPerformAssertions();
    }

    public function testWithStripeSuccess(): void
    {
        $service = $this->getContainer()->get(PurchaseService::class);
        $wareBuilder = $this->getContainer()->get(WareBuilder::class);
        $couponBuilder = $this->getContainer()->get(CouponBuilder::class);

        $ware = $wareBuilder->withName('Iphone')->withPrice(new Price(euro: 100, cent: 0))->build();
        $coupon = $couponBuilder->withType(CodeTypeEnum::PERCENTAGE)->withPercentage(6)->build();

        $service->purchase(
            productId: $ware->getId(),
            taxCode: 'GR123456789',
            couponCode: $coupon->getCode(),
            processor: PaymentProcessorsEnum::STRIPE,
        );
        $this->expectNotToPerformAssertions();
    }
}
