<?php

namespace App\Domain\Ware\ValueObject;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Exception\ValidatorException;

final class Price
{
    /**
     * @psalm-param int<0,max> $euro
     * @psalm-param int<0,99> $penny
     */
    public function __construct(
        #[Assert\GreaterThanOrEqual(value: 0, message: 'Euro must be positive or zero')] #[
            Assert\Type(type: 'integer', message: 'Euro must be an integer'),
        ]
        public int $euro,
        #[Assert\GreaterThanOrEqual(value: 0, message: 'Penny must be positive or zero')] #[
            Assert\LessThan(value: 100, message: 'Penny must be between 0 and 99'),
        ]
        #[Assert\Type(type: 'integer', message: 'Penny must be an integer')] public int $penny,
    ) {}

    public function toArray(): array
    {
        return [
            'euro' => $this->euro,
            'penny' => $this->penny,
        ];
    }

    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context): void
    {
        if ($this->euro === 0 && $this->penny === 0) {
            $context->buildViolation('Both euro and penny cannot be zero.')->atPath('euro')->addViolation();
        }
    }
}
