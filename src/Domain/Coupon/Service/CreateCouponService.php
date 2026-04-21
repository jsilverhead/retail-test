<?php

namespace App\Domain\Coupon\Service;

use App\Domain\Coupon\Coupon;
use App\Domain\Coupon\Exception\CouponWithThisNameAlreadyExistsException;
use App\Domain\Coupon\Repository\CouponRepositoryInterface;
use App\Domain\Coupon\Service\Dto\CreateCouponDto;
use App\Infrastructure\Validator\DataValidatorInterface;

final readonly class CreateCouponService
{
    public function __construct(
        private CouponRepositoryInterface $repository,
        private DataValidatorInterface $validator,
    ) {
    }

    public function create(CreateCouponDto $dto): Coupon
    {
        $this->validator->validate($dto);

        if ($this->repository->getCouponByCode($dto->code)) {
            throw new CouponWithThisNameAlreadyExistsException();
        }

        $coupon = new Coupon(
            code: $dto->code,
            type: $dto->codeType,
            percentage: $dto->percentage,
            fixedValue: $dto->fixedValue,
        );

        $this->repository->add($coupon);

        return $coupon;
    }
}
