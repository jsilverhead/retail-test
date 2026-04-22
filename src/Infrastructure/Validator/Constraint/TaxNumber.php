<?php

namespace App\Infrastructure\Validator\Constraint;

use App\Infrastructure\Validator\TaxNumberValidator;
use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
final class TaxNumber extends Constraint
{
    public string $message = 'The tax number is not valid.';

    public function validatedBy(): string
    {
        return TaxNumberValidator::class;
    }
}
