<?php

use App\Infrastructure\Validator\TaxNumberValidator;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services->defaults()->autowire();

    $services->set(TaxNumberValidator::class)->public();
};
