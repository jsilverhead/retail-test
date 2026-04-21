<?php

namespace App\Infrastructure\Calculator\Enum;

enum CountriesWithTaxEnum: string
{
    /**
     * @psalm-return list<non-empty-string>
     */
    public static function getValidCodes(): array
    {
        return array_column(CountriesWithTaxEnum::cases(), 'value');
    }

    public function getTaxRate(): float
    {
        return match ($this) {
            self::DE => 0.19,
            self::IT => 0.22,
            self::FR => 0.2,
            self::GR => 0.24,
        };
    }

    /**
     * @psalm-return non-empty-string
     */
    public function getTaxRegexp(): string
    {
        return match ($this) {
            self::DE => '/^DE\d{9}$/',
            self::IT => '/^IT\d{11}$/',
            self::FR => '/^FR[A-Z]{2}\d{9}$/',
            self::GR => '/^GR\d{9}$/',
        };
    }
    case DE = 'DE';
    case FR = 'FR';
    case GR = 'GR';
    case IT = 'IT';
}
