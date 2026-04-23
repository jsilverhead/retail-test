<?php

namespace App\Domain\Coupon\Exception;

use App\Infrastructure\Exception\ServiceException;

final class CouponWithThisNameAlreadyExistsException extends ServiceException
{
    public function getDescription(): string
    {
        return 'Coupon with the this name already exists';
    }

    public function getType(): string
    {
        return 'coupon_with_this_name_already_exists';
    }
}
