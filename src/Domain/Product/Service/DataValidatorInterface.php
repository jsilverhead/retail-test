<?php

namespace App\Domain\Product\Service;

interface DataValidatorInterface
{
    public function validate(mixed $data): void;
}
