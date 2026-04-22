<?php

namespace App\Domain\Ware\Service;

use App\Domain\Ware\ValueObject\Price;

interface PaymentProcessorInterface
{
    public function process(Price $price): void;
}
