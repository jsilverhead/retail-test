<?php

namespace App\Infrastructure\Http\User\Dto;

use App\Domain\Product\Enum\PaymentProcessorsEnum;
use App\Infrastructure\Validator\Constraint\TaxNumber;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class PurchaseDto
{
    public function __construct(
        #[Assert\NotNull] #[Assert\Positive] public int $productId,
        #[Assert\NotBlank] #[Assert\NotNull] #[TaxNumber] public string $taxCode,
        #[Assert\NotBlank] public ?string $couponCode,
        #[Assert\NotBlank] public PaymentProcessorsEnum $paymentProcessor,
    ) {
    }
}
