<?php

namespace App\Domain\Coupon\Exception;

use App\Infrastructure\Exception\ServiceException;

final class CouponWithThisNameAlreadyExistsException extends ServiceException
{
    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function getDescription(): string
    {
        return 'Coupon with the this name already exists';
    }

    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function getType(): string
    {
        return 'coupon_with_this_name_already_exists';
    }
}
