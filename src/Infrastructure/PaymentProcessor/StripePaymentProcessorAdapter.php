<?php

namespace App\Infrastructure\PaymentProcessor;

use App\Domain\Ware\Exception\ProcessPaymentFailedException;
use App\Domain\Ware\Service\PaymentProcessorInterface;
use App\Domain\Ware\ValueObject\Price;
use Systemeio\TestForCandidates\PaymentProcessor\StripePaymentProcessor;

final readonly class StripePaymentProcessorAdapter implements PaymentProcessorInterface
{
    public function __construct(private StripePaymentProcessor $stripePaymentProcessor)
    {
    }

    public function process(Price $price): void
    {
        $processed = $this->stripePaymentProcessor->processPayment($price->toFloat());

        if (!$processed) {
            throw new ProcessPaymentFailedException();
        }
    }
}
