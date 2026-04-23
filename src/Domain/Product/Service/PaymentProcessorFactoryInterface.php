<?php

namespace App\Domain\Product\Service;

use App\Domain\Product\Enum\PaymentProcessorsEnum;

interface PaymentProcessorFactoryInterface
{
    public function getProcessor(PaymentProcessorsEnum $processor): PaymentProcessorInterface;
}
