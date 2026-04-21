<?php

namespace App\Tests\Unit;

use App\Domain\Coupon\Enum\CodeTypeEnum;
use App\Domain\Coupon\Service\Dto\CreateCouponDto;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @internal
 */
#[CoversClass(CreateCouponDto::class)]
final class CreateCouponDtoValidationTest extends WebTestCase
{
    #[DataProvider('provideFailCases')]
    public function testFail(CreateCouponDto $dto): void
    {
        $validator = $this->getContainer()->get(ValidatorInterface::class);

        $violations = $validator->validate($dto);

        self::assertTrue($violations->count() > 0);
    }

    /**
     * @psalm-return list<array{CreateCouponDto}>
     */
    public static function provideFailCases(): iterable
    {
        return [
            [
                new CreateCouponDto(
                    code: uniqid(),
                    codeType: CodeTypeEnum::PERCENTAGE,
                    percentage: null,
                    fixedValue: null,
                ),
            ],
            [
                new CreateCouponDto(
                    code: uniqid(),
                    codeType: CodeTypeEnum::FIXED_VALUE,
                    percentage: null,
                    fixedValue: null,
                ),
            ],
            [
                new CreateCouponDto(
                    code: uniqid(),
                    codeType: CodeTypeEnum::PERCENTAGE,
                    percentage: 120,
                    fixedValue: null,
                ),
            ],
            [new CreateCouponDto(code: uniqid(), codeType: CodeTypeEnum::PERCENTAGE, percentage: -1, fixedValue: null)],
            [
                new CreateCouponDto(
                    code: uniqid(),
                    codeType: CodeTypeEnum::FIXED_VALUE,
                    percentage: null,
                    fixedValue: -1,
                ),
            ],
            [new CreateCouponDto(code: uniqid(), codeType: CodeTypeEnum::PERCENTAGE, percentage: 15, fixedValue: 15)],
        ];
    }

    #[DataProvider('provideSuccessCases')]
    public function testSuccess(CreateCouponDto $dto): void
    {
        $validator = $this->getContainer()->get(ValidatorInterface::class);

        $violations = $validator->validate($dto);

        self::assertCount(0, $violations);
    }

    /**
     * @psalm-return list<array{CreateCouponDto}>
     */
    public static function provideSuccessCases(): iterable
    {
        return [
            [new CreateCouponDto(code: uniqid(), codeType: CodeTypeEnum::PERCENTAGE, percentage: 20, fixedValue: null)],
            [
                new CreateCouponDto(
                    code: uniqid(),
                    codeType: CodeTypeEnum::FIXED_VALUE,
                    percentage: null,
                    fixedValue: 10,
                ),
            ],
        ];
    }
}
