<?php

namespace App\Infrastructure\Validator;

interface DataValidatorInterface
{
    public function validate(mixed $data): void;
}
