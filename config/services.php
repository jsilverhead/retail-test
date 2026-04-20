<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services->defaults()->autowire();

    $container->import('packages/*.php');
    $container->import('domain/*.php');
    $container->import('infrastructure/*.php');
    $container->import('infrastructure/*/*.php');
    $container->import('infrastructure/*/*/*.php');

    $container->import('packages/dama_doctrine_test.php');
};
