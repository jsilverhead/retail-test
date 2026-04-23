<?php

namespace App\Tests\Functional\Domain\Product;

use App\Domain\Product\Exception\ProductWithThisNameAlreadyExistsException;
use App\Domain\Product\Service\CreateProductService;
use App\Domain\Product\Service\Dto\CreateProductDto;
use App\Domain\Product\ValueObject\Price;
use App\Tests\Builder\ProductBuilder;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 */
#[CoversClass(CreateProductService::class)]
final class CreateProductServiceTest extends WebTestCase
{
    public function testNameAlreadyExistsFail(): void
    {
        $name = 'iPhone';
        $price = new Price(euro: 100, cent: 0);
        $dto = new CreateProductDto(name: $name, price: $price);

        $builder = $this->getContainer()->get(ProductBuilder::class);
        $builder->withName($name)->build();

        $service = $this->getContainer()->get(CreateProductService::class);

        $this->expectException(ProductWithThisNameAlreadyExistsException::class);
        $service->create($dto);
    }

    public function testSuccess(): void
    {
        $name = 'iPhone';
        $price = new Price(euro: 100, cent: 0);
        $dto = new CreateProductDto(name: $name, price: $price);

        $service = $this->getContainer()->get(CreateProductService::class);
        $product = $service->create($dto);

        self::assertSame(expected: $name, actual: $product->getName());
        self::assertSame(expected: $price, actual: $product->getPrice());
    }
}
