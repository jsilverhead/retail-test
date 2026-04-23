<?php

namespace App\Domain\Product\Service;

use App\Domain\Product\Exception\ProductWithThisNameAlreadyExistsException;
use App\Domain\Product\Product;
use App\Domain\Product\Repository\ProductRepositoryInterface;
use App\Domain\Product\Service\Dto\CreateProductDto;

final readonly class CreateProductService
{
    public function __construct(
        private ProductRepositoryInterface $repository,
        private DataValidatorInterface $validator,
    ) {
    }

    public function create(CreateProductDto $dto): Product
    {
        $this->validator->validate($dto);

        if (null !== $this->repository->getByName($dto->name)) {
            throw new ProductWithThisNameAlreadyExistsException();
        }

        $product = new Product(name: $dto->name, price: $dto->price);

        $this->repository->add($product);

        return $product;
    }
}
