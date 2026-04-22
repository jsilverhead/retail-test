<?php

namespace App\Domain\Ware\Repository;

use App\Domain\Ware\Ware;

interface WareRepositoryInterface
{
    public function add(Ware $warehouse): void;

    public function getByIdOrFail(int $id): Ware;

    public function getByName(string $name): ?Ware;
}
