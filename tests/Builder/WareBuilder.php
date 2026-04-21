<?php

namespace App\Tests\Builder;

use App\Domain\Ware\Service\CreateWareService;
use App\Domain\Ware\Service\Dto\CreateWareDto;
use App\Domain\Ware\ValueObject\Price;
use App\Domain\Ware\Ware;
use Doctrine\ORM\EntityManagerInterface;

final class WareBuilder
{
    /** @psalm-var non-empty-string|null $name */
    private ?string $name = null;

    private ?Price $price = null;

    public function __construct(
        private readonly CreateWareService $createWareService,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function build(): Ware
    {
        $name = $this->name ?? uniqid('ware_', true);
        $price = $this->price ?? new Price(euro: 100, cent: 0);
        $dto = new CreateWareDto(name: $name, price: $price);

        $ware = $this->createWareService->create($dto);

        $this->entityManager->flush();

        return $ware;
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
