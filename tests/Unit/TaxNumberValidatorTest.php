<?php

namespace App\Tests\Unit;

use App\Infrastructure\Calculator\Enum\CountriesWithTaxEnum;
use App\Infrastructure\Validator\TaxNumberValidator;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversClass(TaxNumberValidator::class)]
final class TaxNumberValidatorTest extends TestCase
{
    private TaxNumberValidator $validator;

    /**
     * @psalm-param array{
     *     taxNumber: non-empty-string,
     *     expectedException: non-empty-string
     * }  $payload
     */
    #[DataProvider('provideFailCasesCases')]
    public function testFailCases(array $payload): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($payload['expectedException']);
        $this->validator->validateAndReturnCountry($payload['taxNumber']);
    }

    /**
     * @psalm-return iterable<array-key, array{0: array{taxNumber: non-empty-string, expectedException: non-empty-string}}>
     */
    public static function provideFailCasesCases(): iterable
    {
        return [
            [['taxNumber' => 'DEAB3456789', 'expectedException' => 'Invalid Tax number']],
            [['taxNumber' => 'DE1234567890', 'expectedException' => 'Invalid Tax number']],
            [['taxNumber' => 'DE123456', 'expectedException' => 'Invalid Tax number']],
            [['taxNumber' => 'ITAB345678901', 'expectedException' => 'Invalid Tax number']],
            [['taxNumber' => 'IT123456789012', 'expectedException' => 'Invalid Tax number']],
            [['taxNumber' => 'IT123456789', 'expectedException' => 'Invalid Tax number']],
            [['taxNumber' => 'FR123456789', 'expectedException' => 'Invalid Tax number']],
            [['taxNumber' => 'FRYY123456789012', 'expectedException' => 'Invalid Tax number']],
            [['taxNumber' => 'FRYY12345', 'expectedException' => 'Invalid Tax number']],
            [['taxNumber' => 'GRAB3456789', 'expectedException' => 'Invalid Tax number']],
            [['taxNumber' => 'GR1234567890', 'expectedException' => 'Invalid Tax number']],
            [['taxNumber' => 'GR123456', 'expectedException' => 'Invalid Tax number']],
            [['taxNumber' => 'AU123456789', 'expectedException' => 'Country code not supported']],
        ];
    }

    /**
     * @psalm-param array{
     *     taxNumber: non-empty-string,
     *     country: CountriesWithTaxEnum
     * }  $payload
     */
    #[DataProvider('provideSuccessCasesCases')]
    public function testSuccessCases(array $payload): void
    {
        $country = $this->validator->validateAndReturnCountry($payload['taxNumber']);

        self::assertSame($payload['country'], $country);
    }

    /**
     * @psalm-return iterable<array-key, array{0: array{taxNumber: non-empty-string, country: CountriesWithTaxEnum}}>
     */
    public static function provideSuccessCasesCases(): iterable
    {
        return [
            [['taxNumber' => 'DE123456789', 'country' => CountriesWithTaxEnum::DE]],
            [['taxNumber' => 'IT12345678901', 'country' => CountriesWithTaxEnum::IT]],
            [['taxNumber' => 'FRYY123456789', 'country' => CountriesWithTaxEnum::FR]],
            [['taxNumber' => 'GR123456789', 'country' => CountriesWithTaxEnum::GR]],
        ];
    }

    protected function setUp(): void
    {
        $this->validator = new TaxNumberValidator();
    }
}
