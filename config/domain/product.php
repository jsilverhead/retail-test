<?php

use App\Domain\Product\Repository\ProductRepositoryInterface;
use App\Domain\Product\Service\CountryExtractorInterface;
use App\Infrastructure\Calculator\PriceCalculator;
use App\Infrastructure\Calculator\PriceCalculatorInterface;
use App\Infrastructure\Repository\Product\ProductRepository;
use App\Infrastructure\Validator\CountryExtractor;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Config\DoctrineConfig;

use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

return static function (ContainerConfigurator $container, DoctrineConfig $doctrineConfig) {
    $entityManager = $doctrineConfig->orm()->entityManager('default');
    $entityManager
        ->mapping('App\Domain\Product')
        ->type('attribute')
        ->prefix('App\Domain\Product')
        ->dir(param('kernel.project_dir')->__toString() . '/src/Domain/Product');

    $services = $container->services();
    $services->defaults()->autowire();

    $services->load(
        'App\Domain\Product\Service\\',
        param('kernel.project_dir')->__toString() . '/src/Domain/Product/Service',
    );
    $services->load(
        'App\Infrastructure\Repository\Product\\',
        param('kernel.project_dir')->__toString() . '/src/Infrastructure/Repository/Product',
    );

    $services->set(ProductRepositoryInterface::class, ProductRepository::class);
    $services->set(PriceCalculatorInterface::class, PriceCalculator::class);
    $services->set(CountryExtractorInterface::class, CountryExtractor::class);
};
