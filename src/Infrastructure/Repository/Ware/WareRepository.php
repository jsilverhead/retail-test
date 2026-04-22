<?php

namespace App\Infrastructure\Repository\Ware;

use App\Domain\Ware\Repository\WareRepositoryInterface;
use App\Domain\Ware\Ware;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ware>
 */
final class WareRepository extends ServiceEntityRepository implements WareRepositoryInterface
{
    /**
     * @psalm-suppress UnusedParam
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct(registry: $registry, entityClass: Ware::class);
    }

    public function add(Ware $warehouse): void
    {
        $this->getEntityManager()->persist($warehouse);
    }

    /**
     * @psalm-suppress UnusedParam
     */
    public function getById(int $id): ?Ware
    {
        /** @psalm-var Ware|null $ware */
        $ware = $this->createQueryBuilder('w')
            ->where('w.id = :id')
            ->setParameter('id', $id, Types::INTEGER)
            ->getQuery()
            ->getOneOrNullResult();

        return $ware;
    }

    public function getByIdOrFail(int $id): Ware
    {
        $ware = $this->getById($id);

        if (null === $ware) {
            throw new EntityNotFoundException(Ware::class);
        }

        return $ware;
    }

    public function getByName(string $name): ?Ware
    {
        /** @psalm-var Ware|null $ware */
        $ware = $this->createQueryBuilder('w')
            ->where('w.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();

        return $ware;
    }
}
