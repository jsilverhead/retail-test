<?php

namespace App\Infrastructure\Http\User\Dto;

use App\Infrastructure\Validator\Constraint\TaxNumber;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class CalculatePriceDto
{
    public function __construct(
        #[Assert\NotNull] #[Assert\Positive] public int $productId,
        #[Assert\NotBlank] #[Assert\NotNull] #[TaxNumber] public string $taxCode,
        #[Assert\NotBlank(allowNull: true)] public ?string $couponCode,
    ) {
    }
}
