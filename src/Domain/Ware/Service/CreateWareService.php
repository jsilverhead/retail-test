<?php

namespace App\Domain\Ware\Service;

use App\Domain\Ware\Exception\WareWithThisNameAlreadyExistsException;
use App\Domain\Ware\Repository\WareRepositoryInterface;
use App\Domain\Ware\Service\Dto\CreateWareDto;
use App\Domain\Ware\Ware;

final readonly class CreateWareService
{
    public function __construct(private WareRepositoryInterface $repository)
    {
    }

    public function create(CreateWareDto $dto): Ware
    {
        if (null !== $this->repository->getByName($dto->name)) {
            throw new WareWithThisNameAlreadyExistsException();
        }

        $ware = new Ware(name: $dto->name, price: $dto->price);

        $this->repository->add($ware);

        return $ware;
    }
}
