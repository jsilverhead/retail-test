<?php

namespace App\Domain\Ware\Service;

use App\Domain\Ware\Enum\PaymentProcessorsEnum;

final readonly class PurchaseService
{
    public function __construct(
        private CalculatePriceService $calculatePriceService,
        private PaymentProcessorFactoryInterface $paymentProcessorFactory,
    ) {
    }

    public function purchase(
        int $productId,
        string $taxCode,
        ?string $couponCode,
        PaymentProcessorsEnum $processor,
    ): void {
        $price = $this->calculatePriceService->calculate(
            productId: $productId,
            taxCode: $taxCode,
            couponCode: $couponCode,
        );

        $paymentProcessor = $this->paymentProcessorFactory->getProcessor($processor);
        $paymentProcessor->process($price);
    }
}
