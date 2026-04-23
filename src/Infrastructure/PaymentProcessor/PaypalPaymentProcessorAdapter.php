<?php

namespace App\Infrastructure\PaymentProcessor;

use App\Domain\Ware\Exception\ProcessPaymentFailedException;
use App\Domain\Ware\Service\PaymentProcessorInterface;
use App\Domain\Ware\ValueObject\Price;
use Exception;
use Systemeio\TestForCandidates\PaymentProcessor\PaypalPaymentProcessor;

final readonly class PaypalPaymentProcessorAdapter implements PaymentProcessorInterface
{
    public function __construct(private PaypalPaymentProcessor $paypalPaymentProcessor) {}

    public function process(Price $price): void
    {
        try {
            $this->paypalPaymentProcessor->pay($price->toCents());
        } catch (Exception $exception) {
            throw new ProcessPaymentFailedException($exception->getMessage());
        }
    }
}
