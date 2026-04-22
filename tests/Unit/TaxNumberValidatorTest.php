<?php

namespace App\Tests\Unit;

use App\Infrastructure\Validator\Constraint\TaxNumber;
use App\Infrastructure\Validator\CountryExtractor;
use App\Infrastructure\Validator\TaxNumberValidator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 * @template-extends ConstraintValidatorTestCase<TaxNumberValidator>
 *
 * @internal
 */
#[CoversClass(TaxNumberValidator::class)]
final class TaxNumberValidatorTest extends ConstraintValidatorTestCase
{
    #[DataProvider('provideInvalidTaxNumberCases')]
    public function testInvalidTaxNumber(string $taxNumber, string $expectedMessage): void
    {
        $this->validator->validate($taxNumber, new TaxNumber());

        $violations = $this->context->getViolations();
        self::assertCount(1, $violations);
        self::assertSame($expectedMessage, $violations->get(0)->getMessage());
    }

    /**
     * @psalm-return iterable<array-key, array{string, string}>
     */
    public static function provideInvalidTaxNumberCases(): iterable
    {
        yield 'DE with letters' => ['DEAB3456789', 'The tax number is not valid.'];

        yield 'DE too long' => ['DE1234567890', 'The tax number is not valid.'];

        yield 'DE too short' => ['DE123456', 'The tax number is not valid.'];

        yield 'IT with letters' => ['ITAB345678901', 'The tax number is not valid.'];

        yield 'IT too long' => ['IT123456789012', 'The tax number is not valid.'];

        yield 'IT too short' => ['IT123456789', 'The tax number is not valid.'];

        yield 'FR without letters' => ['FR123456789', 'The tax number is not valid.'];

        yield 'FR too long' => ['FRYY123456789012', 'The tax number is not valid.'];

        yield 'FR too short' => ['FRYY12345', 'The tax number is not valid.'];

        yield 'GR with letters' => ['GRAB3456789', 'The tax number is not valid.'];

        yield 'GR too long' => ['GR1234567890', 'The tax number is not valid.'];

        yield 'GR too short' => ['GR123456', 'The tax number is not valid.'];

        yield 'unsupported country' => ['AU123456789', 'Country code not supported'];
    }

    #[DataProvider('provideValidTaxNumberCases')]
    public function testValidTaxNumber(string $taxNumber): void
    {
        $this->validator->validate($taxNumber, new TaxNumber());
        $this->assertNoViolation();
    }

    /**
     * @psalm-return iterable<array-key, array{string}>
     */
    public static function provideValidTaxNumberCases(): iterable
    {
        yield 'Germany' => ['DE123456789'];

        yield 'Italy' => ['IT12345678901'];

        yield 'France' => ['FRYY123456789'];

        yield 'Greece' => ['GR123456789'];
    }

    protected function createValidator(): TaxNumberValidator
    {
        return new TaxNumberValidator(new CountryExtractor());
    }
}
