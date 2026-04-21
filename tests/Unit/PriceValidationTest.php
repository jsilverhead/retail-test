<?php

namespace App\Tests\Unit;

use App\Domain\Ware\ValueObject\Price;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @internal
 */
#[CoversClass(Price::class)]
final class PriceValidationTest extends WebTestCase
{
    /**
     * @psalm-param array{euro: int, penny: int} $payload
     */
    #[DataProvider('provideFailCases')]
    public function testFail(array $payload): void
    {
        $price = new Price(euro: $payload['euro'], cent: $payload['penny']);
        $validator = $this->getContainer()->get(ValidatorInterface::class);

        $violations = $validator->validate($price);

        self::assertTrue($violations->count() > 0);
    }

    /**
     * @psalm-return iterable<array-key, array{0: array{euro: int, penny: int}}>
     */
    public static function provideFailCases(): iterable
    {
        return [
            [['euro' => 10, 'penny' => 120]],
            [['euro' => -1, 'penny' => 0]],
            [['euro' => 5, 'penny' => -1]],
            [['euro' => 0, 'penny' => 0]],
        ];
    }

    /**
     * @psalm-param array{euro: int, penny: int} $payload
     */
    #[DataProvider('provideSuccessCases')]
    public function testSuccess(array $payload): void
    {
        $price = new Price(euro: $payload['euro'], cent: $payload['penny']);
        $validator = $this->getContainer()->get(ValidatorInterface::class);

        $violations = $validator->validate($price);

        self::assertCount(0, $violations);
    }

    /**
     * @psalm-return iterable<array-key, array{0: array{euro: int, penny: int}}>
     */
    public static function provideSuccessCases(): iterable
    {
        return [[['euro' => 120, 'penny' => 0]], [['euro' => 0, 'penny' => 50]]];
    }
}
