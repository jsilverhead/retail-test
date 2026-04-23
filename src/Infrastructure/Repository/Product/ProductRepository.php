<?php

namespace App\Infrastructure\Repository\Product;

use App\Domain\Product\Product;
use App\Domain\Product\Repository\ProductRepositoryInterface;
use App\Infrastructure\Exception\EntityNotFoundException;
use App\Infrastructure\Exception\Enum\EntityNotFoundEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
final class ProductRepository extends ServiceEntityRepository implements ProductRepositoryInterface
{
    /**
     * @psalm-suppress UnusedParam
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct(registry: $registry, entityClass: Product::class);
    }

    public function add(Product $product): void
    {
        $this->getEntityManager()->persist($product);
    }

    /**
     * @psalm-suppress UnusedParam
     */
    public function getById(int $id): ?Product
    {
        /** @psalm-var Product|null $product */
        $product = $this->createQueryBuilder('p')
            ->where('p.id = :id')
            ->setParameter('id', $id, Types::INTEGER)
            ->getQuery()
            ->getOneOrNullResult();

        return $product;
    }

    public function getByIdOrFail(int $id): Product
    {
        $product = $this->getById($id);

        if (null === $product) {
            throw new EntityNotFoundException(EntityNotFoundEnum::PRODUCT);
        }

        return $product;
    }

    public function getByName(string $name): ?Product
    {
        /** @psalm-var Product|null $product */
        $product = $this->createQueryBuilder('p')
            ->where('p.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();

        return $product;
    }
}
