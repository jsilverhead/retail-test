<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services->defaults()->autowire();

    $container->import('packages/*.php');
    $container->import('domain/*.php');
    //    $container->import('infrastructure/*.php');
    $container->import('infrastructure/*/*.php');
    //    $container->import('infrastructure/*/*/*.php');

    if ('test' === $container->env()) {
        $container->import('packages/test/*.php');
    }

    $services->load('App\Tests\Builder\\', param('kernel.project_dir')->__toString() . '/tests/Builder')->public();
};
