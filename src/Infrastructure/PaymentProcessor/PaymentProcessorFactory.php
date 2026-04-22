<?php

namespace App\Infrastructure\PaymentProcessor;

use App\Domain\Ware\Enum\PaymentProcessorsEnum;
use App\Domain\Ware\Service\PaymentProcessorFactoryInterface;
use App\Domain\Ware\Service\PaymentProcessorInterface;
use LogicException;

final readonly class PaymentProcessorFactory implements PaymentProcessorFactoryInterface
{
    public function __construct(
        private PaypalPaymentProcessorAdapter $paypalPaymentProcessorAdapter,
        private StripePaymentProcessorAdapter $stripePaymentProcessorAdapter,
    ) {
    }

    public function getProcessor(PaymentProcessorsEnum $processor): PaymentProcessorInterface
    {
        return match ($processor) {
            PaymentProcessorsEnum::STRIPE => $this->stripePaymentProcessorAdapter,
            PaymentProcessorsEnum::PAYPAL => $this->paypalPaymentProcessorAdapter,
            default => throw new LogicException('Payment processor not implemented'),
        };
    }
}
