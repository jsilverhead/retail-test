<?php

namespace App\Tests\Unit;

use App\Domain\Ware\ValueObject\Price;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PriceValidationTest extends WebTestCase
{
    /**
     * @psalm-return list<array<non-empty-string>>
     */
    public static function provideSuccessfulCreatingCases(): iterable
    {
        return [[['euro' => 120, 'penny' => 0]], [['euro' => 0, 'penny' => 50]]];
    }

    public static function provideUnsuccessfulCreatingCases(): iterable
    {
        return [
            [['euro' => 10, 'penny' => 120]],
            [['euro' => -1, 'penny' => 0]],
            [['euro' => 5, 'penny' => -1]],
            [['euro' => 0, 'penny' => 0]],
        ];
    }

    #[DataProvider('provideSuccessfulCreatingCases')]
    public function testSuccess(array $payload): void
    {
        $price = new Price(euro: $payload['euro'], penny: $payload['penny']);
        $validator = $this->getContainer()->get(ValidatorInterface::class);

        $violations = $validator->validate($price);

        $this->assertCount(0, $violations);
    }

    #[DataProvider('provideUnsuccessfulCreatingCases')]
    public function testPennySuccess(array $payload): void
    {
        $price = new Price(euro: $payload['euro'], penny: $payload['penny']);
        $validator = $this->getContainer()->get(ValidatorInterface::class);

        $violations = $validator->validate($price);

        $this->assertTrue($violations->count() > 0);
    }
}
