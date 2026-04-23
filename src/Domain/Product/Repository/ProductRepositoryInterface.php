<?php

namespace App\Domain\Product\Repository;

use App\Domain\Product\Product;

interface ProductRepositoryInterface
{
    public function add(Product $product): void;

    public function getByIdOrFail(int $id): Product;

    public function getByName(string $name): ?Product;
}
