<?php

use App\Domain\Coupon\Repository\CouponRepositoryInterface;
use App\Domain\Coupon\Service\CreateCouponService;
use App\Infrastructure\Repository\Coupon\CouponRepository;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Config\DoctrineConfig;

use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

return static function (ContainerConfigurator $container, DoctrineConfig $doctrineConfig): void {
    $entityManager = $doctrineConfig->orm()->entityManager('default');
    $entityManager
        ->mapping('App\Domain\Coupon\\')
        ->prefix('attribute')
        ->prefix('App\Domain\Coupon')
        ->dir(param('kernel.project_dir')->__toString() . '/src/Domain/Coupon');

    $services = $container->services();
    $services->defaults()->autowire();

    $services->load(
        'App\Domain\Coupon\Service\\',
        param('kernel.project_dir')->__toString() . '/src/Domain/Coupon/Service',
    );

    $services->load(
        'App\Infrastructure\Repository\Coupon\\',
        param('kernel.project_dir')->__toString() . '/src/Infrastructure/Repository/Coupon',
    );

    $services->set(CreateCouponService::class)->public();
    $services->alias(CouponRepositoryInterface::class, CouponRepository::class);
};
