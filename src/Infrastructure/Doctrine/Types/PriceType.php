<?php

namespace App\Infrastructure\Doctrine\Types;

use App\Domain\Ware\ValueObject\Price;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use InvalidArgumentException;

/**
 * @psalm-api
 */
final class PriceType extends Type
{
    public function convertToDatabaseValue(mixed $value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        if (!$value instanceof Price) {
            throw new InvalidArgumentException('Value must be an instance of Price');
        }

        return json_encode($value->toArray(), \JSON_THROW_ON_ERROR);
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?Price
    {
        if (!\is_string($value)) {
            throw new InvalidArgumentException('Value must be a string');
        }

        if ('' === $value) {
            throw new InvalidArgumentException('Value cannot be empty');
        }

        /** @psalm-var array{euro: int<0,max>, cent: int<0,99>} $decodedValue */
        $decodedValue = json_decode(json: $value, associative: true, depth: 512, flags: \JSON_THROW_ON_ERROR);

        return new Price(euro: $decodedValue['euro'], cent: $decodedValue['cent']);
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        $column['jsonb'] = true;

        return $platform->getJsonTypeDeclarationSQL($column);
    }
}
