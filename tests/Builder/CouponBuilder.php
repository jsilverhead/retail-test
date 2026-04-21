<?php

namespace App\Tests\Builder;

use App\Domain\Coupon\Coupon;
use App\Domain\Coupon\Enum\CodeTypeEnum;
use App\Domain\Coupon\Service\CreateCouponService;
use App\Domain\Coupon\Service\Dto\CreateCouponDto;
use App\Infrastructure\Validator\DataValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;

final class CouponBuilder
{
    /** @psalm-var non-empty-string|null $code */
    private ?string $code = null;

    private CodeTypeEnum $codeType = CodeTypeEnum::FIXED_VALUE;

    private ?int $fixedValue = null;

    private ?int $percentage = null;

    public function __construct(
        private readonly CreateCouponService $createCouponService,
        private readonly EntityManagerInterface $entityManager,
        private readonly DataValidatorInterface $validator,
    ) {
    }

    public function build(): Coupon
    {
        $fixedValue = function (CodeTypeEnum $type): ?int {
            if (CodeTypeEnum::FIXED_VALUE === $type) {
                return $this->fixedValue ?? 15;
            }

            return null;
        };

        $percentage = function (CodeTypeEnum $type): ?int {
            if (CodeTypeEnum::PERCENTAGE === $type) {
                return $this->percentage ?? 20;
            }

            return null;
        };

        $dto = new CreateCouponDto(
            code: $this->code ?? uniqid('code_', true),
            codeType: $this->codeType,
            percentage: $percentage($this->codeType),
            fixedValue: $fixedValue($this->codeType),
        );

        $this->validator->validate($dto);

        $coupon = $this->createCouponService->create($dto);

        $this->entityManager->flush();

        return $coupon;
    }

    /**
     * @psalm-param non-empty-string $code
     */
    public function withCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function withFixedValue(int $fixedValue): self
    {
        $this->fixedValue = $fixedValue;

        return $this;
    }

    public function withPercentage(int $percentage): self
    {
        $this->percentage = $percentage;

        return $this;
    }

    public function withType(CodeTypeEnum $codeType): self
    {
        $this->codeType = $codeType;

        return $this;
    }
}
