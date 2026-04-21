<?php

namespace App\Domain\Ware\ValueObject;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

final class Price
{
    public function __construct(
        #[Assert\GreaterThanOrEqual(value: 0, message: 'Euro must be positive or zero')] #[Assert\Type(type: 'integer', message: 'Euro must be an integer'),]
        public int $euro,
        #[Assert\GreaterThanOrEqual(value: 0, message: 'Cent must be positive or zero')] #[Assert\LessThan(value: 100, message: 'Cent must be between 0 and 99'),]
        #[Assert\Type(type: 'integer', message: 'Cent must be an integer')] public int $cent,
    ) {
    }

    public static function fromCents(int $cents): self
    {
        return new self(euro: (int) ($cents / 100), cent: $cents % 100);
    }

    public function toArray(): array
    {
        return [
            'euro' => $this->euro,
            'cent' => $this->cent,
        ];
    }

    public function toCents(): int
    {
        return $this->euro * 100 + $this->cent;
    }

    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context): void
    {
        if (0 === $this->euro && 0 === $this->cent) {
            $context->buildViolation('Both euro and cent cannot be zero.')->atPath('euro')->addViolation();
        }
    }
}
