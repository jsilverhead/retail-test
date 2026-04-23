<?php

namespace App\Infrastructure\Http\User\Denormalizer;

use App\Domain\Product\Enum\PaymentProcessorsEnum;
use App\Infrastructure\Http\Payload\Payload;
use App\Infrastructure\Http\User\Dto\PurchaseDto;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final readonly class PurchaseDenormalizer
{
    public function denormalize(Payload $payload): PurchaseDto
    {
        /** @psalm-var array{
         *     product: int,
         *     taxNumber: string,
         *     couponCode: string|null,
         *     paymentProcessor: string,
         * } $arguments
         */
        $arguments = $payload->arguments;

        if (!\array_key_exists('product', $arguments)) {
            throw new BadRequestHttpException('Missing field: product');
        }

        if (!\array_key_exists('taxNumber', $arguments)) {
            throw new BadRequestHttpException('Missing field: taxNumber');
        }

        if (!\array_key_exists('couponCode', $arguments)) {
            throw new BadRequestHttpException('Missing field: taxNumber');
        }

        if (!\array_key_exists('paymentProcessor', $arguments)) {
            throw new BadRequestHttpException('Missing field: taxNumber');
        }

        $paymentProcessor = $arguments['paymentProcessor'];

        $processorAsEnum = PaymentProcessorsEnum::tryFrom($paymentProcessor);

        if (null === $processorAsEnum) {
            throw new BadRequestHttpException('Unsupported paymentProcessor');
        }

        return new PurchaseDto(
            productId: $arguments['product'],
            taxCode: $arguments['taxNumber'],
            couponCode: $arguments['couponCode'] ?? null,
            paymentProcessor: $processorAsEnum,
        );
    }
}
