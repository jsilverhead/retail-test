<?php

namespace App\Tests\Functional\Domain\Ware;

use App\Domain\Coupon\Enum\CodeTypeEnum;
use App\Domain\Coupon\Service\CalculatePriceService;
use App\Domain\Ware\ValueObject\Price;
use App\Infrastructure\Http\User\Dto\CalculatePriceDto;
use App\Tests\Builder\CouponBuilder;
use App\Tests\Builder\WareBuilder;
use Doctrine\ORM\EntityNotFoundException;
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
        $wareBuilder = $this->getContainer()->get(WareBuilder::class);
        $couponBuilder = $this->getContainer()->get(CouponBuilder::class);

        $ware = $wareBuilder->withPrice(new Price(euro: 100, cent: 0))->withName('iPhone')->build();

        $coupon = $couponBuilder->withType(CodeTypeEnum::PERCENTAGE)->withPercentage(6)->build();

        $dto = new CalculatePriceDto(productId: $ware->getId(), taxCode: 'RU123456789', couponCode: $coupon->getCode());

        $this->expectException(BadRequestHttpException::class);
        $service->calculate($dto);
    }

    public function testNotUndefinedCouponFail(): void
    {
        $service = $this->getContainer()->get(CalculatePriceService::class);
        $wareBuilder = $this->getContainer()->get(WareBuilder::class);

        $ware = $wareBuilder->withPrice(new Price(euro: 100, cent: 0))->withName('iPhone')->build();

        $dto = new CalculatePriceDto(productId: $ware->getId(), taxCode: 'DE123456789', couponCode: 'SALE21');

        $this->expectException(EntityNotFoundException::class);
        $service->calculate($dto);
    }

    public function testSuccess(): void
    {
        $service = $this->getContainer()->get(CalculatePriceService::class);
        $wareBuilder = $this->getContainer()->get(WareBuilder::class);
        $couponBuilder = $this->getContainer()->get(CouponBuilder::class);

        $ware = $wareBuilder->withPrice(new Price(euro: 100, cent: 0))->withName('iPhone')->build();
        $coupon = $couponBuilder->withType(CodeTypeEnum::PERCENTAGE)->withPercentage(6)->build();

        $dto = new CalculatePriceDto(productId: $ware->getId(), taxCode: 'GR123456789', couponCode: $coupon->getCode());

        $price = $service->calculate($dto);

        self::assertSame(expected: 116, actual: $price->euro);
        self::assertSame(expected: 56, actual: $price->cent);
    }

    public function testUndefinedWareFail(): void
    {
        $service = $this->getContainer()->get(CalculatePriceService::class);
        $couponBuilder = $this->getContainer()->get(CouponBuilder::class);

        $coupon = $couponBuilder->withType(CodeTypeEnum::PERCENTAGE)->withPercentage(6)->build();

        $dto = new CalculatePriceDto(productId: 5, taxCode: 'GR123456789', couponCode: $coupon->getCode());

        $this->expectException(EntityNotFoundException::class);
        $service->calculate($dto);
    }
}
