<?php

namespace App\Domain\Coupon\Repository;

use App\Domain\Coupon\Coupon;

interface CouponRepositoryInterface
{
    public function add(Coupon $coupon): void;

    public function getCouponByCode(string $code): ?Coupon;

    public function getCouponByCodeOrFail(string $code): Coupon;
}
