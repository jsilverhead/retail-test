<?php

namespace App\Infrastructure\Validator;

use App\Domain\Product\Service\DataValidatorInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final readonly class DataValidator implements DataValidatorInterface
{
    public function __construct(private ValidatorInterface $validator)
    {
    }

    public function validate(mixed $data): void
    {
        $violations = $this->validator->validate($data);

        if (\count($violations) > 0) {
            throw new ValidationFailedException($data, $violations);
        }
    }
}
