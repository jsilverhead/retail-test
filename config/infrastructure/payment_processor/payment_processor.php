<?php

use App\Domain\Ware\Service\PaymentProcessorFactoryInterface;
use App\Infrastructure\PaymentProcessor\PaymentProcessorFactory;
use App\Infrastructure\PaymentProcessor\PaypalPaymentProcessorAdapter;
use App\Infrastructure\PaymentProcessor\StripePaymentProcessorAdapter;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Systemeio\TestForCandidates\PaymentProcessor\PaypalPaymentProcessor;
use Systemeio\TestForCandidates\PaymentProcessor\StripePaymentProcessor;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services->defaults()->autowire();

    $services->set(PaypalPaymentProcessor::class);
    $services->set(StripePaymentProcessor::class);

    $services->set(PaypalPaymentProcessorAdapter::class)->autowire();
    $services->set(StripePaymentProcessorAdapter::class)->autowire();

    $services->set(PaymentProcessorFactoryInterface::class, PaymentProcessorFactory::class);
};
