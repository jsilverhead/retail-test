<?php

namespace App\Domain\Product\Enum;

enum PaymentProcessorsEnum: string
{
    case PAYPAL = 'paypal';
    case STRIPE = 'stripe';
}
