<?php

namespace App\Infrastructure\Repository\Coupon;

use App\Domain\Coupon\Coupon;
use App\Domain\Coupon\Repository\CouponRepositoryInterface;
use App\Infrastructure\Exception\EntityNotFoundException;
use App\Infrastructure\Exception\Enum\EntityNotFoundEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Coupon>
 */
final class CouponRepository extends ServiceEntityRepository implements CouponRepositoryInterface
{
    /**
     * @psalm-suppress UnusedParam
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct(registry: $registry, entityClass: Coupon::class);
    }

    public function add(Coupon $coupon): void
    {
        $this->getEntityManager()->persist($coupon);
    }

    public function getCouponByCode(string $code): ?Coupon
    {
        /** @psalm-var Coupon|null $coupon */
        $coupon = $this->createQueryBuilder('c')
            ->where('c.code = :code')
            ->setParameter('code', $code, Types::STRING)
            ->getQuery()
            ->getOneOrNullResult();

        return $coupon;
    }

    public function getCouponByCodeOrFail(string $code): Coupon
    {
        $coupon = $this->getCouponByCode($code);

        if (null === $coupon) {
            throw new EntityNotFoundException(EntityNotFoundEnum::COUPON);
        }

        return $coupon;
    }
}
