<?php

use App\Domain\Product\Service\DataValidatorInterface;
use App\Infrastructure\Validator\DataValidator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services->defaults()->autowire();

    $services->load(
        'App\Infrastructure\Validator\\',
        param('kernel.project_dir')->__toString() . '/src/Infrastructure/Validator',
    );

    $services->set(DataValidatorInterface::class, DataValidator::class);
};
