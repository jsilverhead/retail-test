<?php

namespace App\Tests\Builder;

use App\Domain\Product\Product;
use App\Domain\Product\Service\CreateProductService;
use App\Domain\Product\Service\Dto\CreateProductDto;
use App\Domain\Product\ValueObject\Price;
use Doctrine\ORM\EntityManagerInterface;

final class ProductBuilder
{
    /** @psalm-var non-empty-string|null $name */
    private ?string $name = null;

    private ?Price $price = null;

    public function __construct(
        private readonly CreateProductService $createWareService,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function build(): Product
    {
        $name = $this->name ?? uniqid('ware_', true);
        $price = $this->price ?? new Price(euro: 100, cent: 0);
        $dto = new CreateProductDto(name: $name, price: $price);

        $product = $this->createWareService->create($dto);

        $this->entityManager->flush();

        return $product;
    }

    /**
     * @psalm-param non-empty-string $name
     */
    public function withName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function withPrice(Price $price): self
    {
        $this->price = $price;

        return $this;
    }
}
