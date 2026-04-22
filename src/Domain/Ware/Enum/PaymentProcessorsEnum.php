<?php

namespace App\Domain\Ware\Enum;

enum PaymentProcessorsEnum: string
{
    case PAYPAL = 'paypal';
    case STRIPE = 'stripe';
}
