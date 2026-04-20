<?php

namespace App\Domain\Ware\Service\Dto;

use App\Domain\Ware\ValueObject\Price;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateWareDto
{
    public function __construct(
        #[Assert\NotBlank] #[Assert\Length(min: 3, max: 100)] public string $name,
        #[Assert\Valid] public Price $price,
    ) {
    }
}
