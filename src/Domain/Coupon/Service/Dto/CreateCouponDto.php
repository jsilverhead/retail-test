<?php

namespace App\Domain\Coupon\Service\Dto;

use App\Domain\Coupon\Enum\CodeTypeEnum;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

final readonly class CreateCouponDto
{
    public function __construct(
        public string $code,
        public CodeTypeEnum $codeType,
        #[Assert\LessThan(100)] #[Assert\GreaterThan(0)] public ?int $percentage = null,
        #[Assert\GreaterThan(0)] public ?int $fixedValue = null,
    ) {
    }

    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context): void
    {
        if (CodeTypeEnum::PERCENTAGE === $this->codeType && null === $this->percentage) {
            $context
                ->buildViolation('For percentage type percentage amount is essential')
                ->atPath('percentage')
                ->addViolation();
        }

        if (CodeTypeEnum::FIXED_VALUE === $this->codeType && null === $this->fixedValue) {
            $context
                ->buildViolation('For fixed value type fixed value amount is essential')
                ->atPath('fixedValue')
                ->addViolation();
        }

        if (null !== $this->percentage && null !== $this->fixedValue) {
            $context
                ->buildViolation('Coupon can not have both percentage and fixed value')
                ->atPath('value')
                ->addViolation();
        }
    }
}
