<?php

namespace App\Domain\Ware\Service;

interface DataValidatorInterface
{
    public function validate(mixed $data): void;
}
