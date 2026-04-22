<?php

use App\Domain\Ware\Repository\WareRepositoryInterface;
use App\Domain\Ware\Service\CountryExtractorInterface;
use App\Domain\Ware\Service\CreateWareService;
use App\Domain\Ware\Service\PurchaseService;
use App\Infrastructure\Calculator\PriceCalculator;
use App\Infrastructure\Calculator\PriceCalculatorInterface;
use App\Infrastructure\Repository\Ware\WareRepository;
use App\Infrastructure\Validator\CountryExtractor;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Config\DoctrineConfig;

use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

return static function (ContainerConfigurator $container, DoctrineConfig $doctrineConfig) {
    $entityManager = $doctrineConfig->orm()->entityManager('default');
    $entityManager
        ->mapping('App\Domain\Ware')
        ->type('attribute')
        ->prefix('App\Domain\Ware')
        ->dir(param('kernel.project_dir')->__toString() . '/src/Domain/Ware');

    $services = $container->services();
    $services->defaults()->autowire();

    $services->load(
        'App\Domain\Ware\Service\\',
        param('kernel.project_dir')->__toString() . '/src/Domain/Ware/Service',
    );
    $services->load(
        'App\Infrastructure\Repository\Ware\\',
        param('kernel.project_dir')->__toString() . '/src/Infrastructure/Repository/Ware',
    );

    $services->set(CreateWareService::class)->public();
    $services->set(PurchaseService::class)->public();
    $services->set(WareRepositoryInterface::class, WareRepository::class);
    $services->set(PriceCalculatorInterface::class, PriceCalculator::class);
    $services->set(CountryExtractorInterface::class, CountryExtractor::class);
};
