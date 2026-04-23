<?php

namespace App\Infrastructure\Exception\Enum;

enum EntityNotFoundEnum: string
{
    case COUPON = 'coupon';
    case PRODUCT = 'product';
}
