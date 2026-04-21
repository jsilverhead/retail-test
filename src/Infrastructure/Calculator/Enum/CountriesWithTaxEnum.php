<?php

namespace App\Infrastructure\Calculator\Enum;

enum CountriesWithTaxEnum: string
{
    public function getTaxRate(): float
    {
        return match ($this) {
            self::DE => 0.19,
            self::IT => 0.22,
            self::FR => 0.2,
            self::GR => 0.24,
        };
    }
    case DE = 'DE';
    case FR = 'FR';
    case GR = 'GR';
    case IT = 'IT';
}
