<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Config\FrameworkConfig;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

return static function (ContainerConfigurator $container, FrameworkConfig $frameworkConfig): void {
    $frameworkConfig->httpMethodOverride(false);
    $frameworkConfig->defaultLocale('en');

    $frameworkConfig->router()->strictRequirements(param('kernel.debug')->__toString())->utf8(true);

    $frameworkConfig->test('test' === $container->env());
};