<?php

namespace App\Tests\Functional\Domain\Coupon;

use App\Domain\Coupon\Enum\CodeTypeEnum;
use App\Domain\Coupon\Exception\CouponWithThisNameAlreadyExistsException;
use App\Domain\Coupon\Service\CreateCouponService;
use App\Domain\Coupon\Service\Dto\CreateCouponDto;
use App\Tests\Builder\CouponBuilder;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 */
#[CoversClass(CreateCouponService::class)]
final class CreateCouponServiceTest extends WebTestCase
{
    public function testCouponCodeAlreadyExistsFail(): void
    {
        $code = 'D12';
        $type = CodeTypeEnum::PERCENTAGE;
        $percents = 20;

        $builder = $this->getContainer()->get(CouponBuilder::class);
        $builder->withCode($code)->build();

        $dto = new CreateCouponDto(code: $code, codeType: $type, percentage: $percents);

        $service = $this->getContainer()->get(CreateCouponService::class);

        $this->expectException(CouponWithThisNameAlreadyExistsException::class);
        $service->create($dto);
    }

    public function testFixedCodeSuccess(): void
    {
        $code = 'D12';
        $type = CodeTypeEnum::FIXED_VALUE;
        $fixedValue = 5;

        $dto = new CreateCouponDto(code: $code, codeType: $type, fixedValue: $fixedValue);

        $service = $this->getContainer()->get(CreateCouponService::class);
        $coupon = $service->create($dto);

        self::assertSame(expected: $code, actual: $coupon->getCode());
        self::assertSame(expected: $type, actual: $coupon->getType());
        self::assertSame(expected: $fixedValue, actual: $coupon->getFixedValue());
    }

    public function testPercentageCodeSuccess(): void
    {
        $code = 'D12';
        $type = CodeTypeEnum::PERCENTAGE;
        $percents = 20;

        $dto = new CreateCouponDto(code: $code, codeType: $type, percentage: $percents);

        $service = $this->getContainer()->get(CreateCouponService::class);
        $coupon = $service->create($dto);

        self::assertSame(expected: $code, actual: $coupon->getCode());
        self::assertSame(expected: $type, actual: $coupon->getType());
        self::assertSame(expected: $percents, actual: $coupon->getPercentage());
    }
}
