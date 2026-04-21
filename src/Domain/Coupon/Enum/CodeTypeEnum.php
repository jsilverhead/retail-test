<?php

namespace App\Domain\Coupon\Enum;

enum CodeTypeEnum: string
{
    case FIXED_VALUE = 'fixed_value';
    case PERCENTAGE = 'percentage';
}
