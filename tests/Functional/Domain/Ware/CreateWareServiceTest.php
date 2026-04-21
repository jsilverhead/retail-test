<?php

namespace App\Tests\Functional\Domain\Ware;

use App\Domain\Ware\Exception\WareWithThisNameAlreadyExistsException;
use App\Domain\Ware\Service\CreateWareService;
use App\Domain\Ware\Service\Dto\CreateWareDto;
use App\Domain\Ware\ValueObject\Price;
use App\Tests\Builder\WareBuilder;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 */
#[CoversClass(CreateWareService::class)]
final class CreateWareServiceTest extends WebTestCase
{
    public function testNameAlreadyExistsFail(): void
    {
        $name = 'iPhone';
        $price = new Price(euro: 100, cent: 0);
        $dto = new CreateWareDto(name: $name, price: $price);

        $builder = $this->getContainer()->get(WareBuilder::class);
        $builder->withName($name)->build();

        $service = $this->getContainer()->get(CreateWareService::class);

        $this->expectException(WareWithThisNameAlreadyExistsException::class);
        $service->create($dto);
    }

    public function testSuccess(): void
    {
        $name = 'iPhone';
        $price = new Price(euro: 100, cent: 0);
        $dto = new CreateWareDto(name: $name, price: $price);

        $service = $this->getContainer()->get(CreateWareService::class);
        $ware = $service->create($dto);

        self::assertSame(expected: $name, actual: $ware->getName());
        self::assertSame(expected: $price, actual: $ware->getPrice());
    }
}
