<?php

namespace App\Infrastructure\Repository\Ware;

use App\Domain\Ware\Repository\WareRepositoryInterface;
use App\Domain\Ware\Ware;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
