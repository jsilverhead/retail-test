<?php

namespace App\Domain\Product\Service\Dto;

use App\Domain\Product\ValueObject\Price;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateProductDto
{
    public function __construct(
        #[Assert\NotBlank] #[Assert\Length(min: 3, max: 100)] public string $name,
        #[Assert\Valid] public Price $price,
    ) {
    }
}
