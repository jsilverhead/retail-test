<?php

namespace App\Infrastructure\Validator;

use App\Infrastructure\Validator\Constraint\TaxNumber;
use InvalidArgumentException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class TaxNumberValidator extends ConstraintValidator
{
    public function __construct(private readonly CountryExtractor $extractor)
    {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof TaxNumber) {
            throw new UnexpectedTypeException($constraint, TaxNumber::class);
        }

        if (!\is_string($value) || '' === $value) {
            return;
        }

        try {
            $country = $this->extractor->extractCountry($value);

            if (!preg_match($country->getTaxRegexp(), $value)) {
                $this->context->buildViolation($constraint->message)->addViolation();
            }
        } catch (InvalidArgumentException $e) {
            $this->context->buildViolation('Country code not supported')->addViolation();
        }
    }
}
