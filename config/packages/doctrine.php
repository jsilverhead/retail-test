<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Config\DoctrineConfig;

use function Symfony\Component\DependencyInjection\Loader\Configurator\env;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

return static function (ContainerConfigurator $container, DoctrineConfig $doctrine): void {
    $connectionName = 'default';
    $dbal = $doctrine->dbal();
    $dbal->connection($connectionName)
        ->url(env('DATABASE_URL'))
        ->logging(param('kernel.debug'))
        ->schemaFilter('/(?<!_seq)$/')
        ->useSavepoints(true);

    $doctrine->orm()->autoGenerateProxyClasses(param('kernel.debug'));

    $entityManager = $doctrine->orm()->entityManager($connectionName);
    $entityManager->namingStrategy('doctrine.orm.naming_strategy.underscore_number_aware')->autoMapping(true);

    $services = $container->services();
    $services->defaults()->autowire();
};
