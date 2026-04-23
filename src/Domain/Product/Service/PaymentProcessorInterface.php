<?php

namespace App\Domain\Product\Service;

use App\Domain\Product\ValueObject\Price;

interface PaymentProcessorInterface
{
    public function process(Price $price): void;
}
