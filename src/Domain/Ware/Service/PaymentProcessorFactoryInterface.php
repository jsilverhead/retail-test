<?php

namespace App\Domain\Ware\Service;

use App\Domain\Ware\Enum\PaymentProcessorsEnum;

interface PaymentProcessorFactoryInterface
{
    public function getProcessor(PaymentProcessorsEnum $processor): PaymentProcessorInterface;
}
