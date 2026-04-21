<?php

namespace App\Tests\Builder;

use App\Domain\Coupon\Coupon;
use App\Domain\Coupon\Enum\CodeTypeEnum;
use App\Domain\Coupon\Service\CreateCouponService;
use App\Domain\Coupon\Service\Dto\CreateCouponDto;
use Doctrine\ORM\EntityManagerInterface;

final class CouponBuilder
{
    /** @psalm-var non-empty-string|null $code */
    private ?string $code = null;

    public function __construct(
        private readonly CreateCouponService $createCouponService,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @psalm-suppress PossiblyUnusedReturnValue
     */
    public function build(): Coupon
    {
        $dto = new CreateCouponDto(
            code: $this->code ?? uniqid('code_', true),
            codeType: CodeTypeEnum::FIXED_VALUE,
            fixedValue: 15,
        );

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
}
