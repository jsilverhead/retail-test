<?php

namespace App\Infrastructure\Http\User\Denormalizer;

use App\Infrastructure\Http\Payload\Payload;
use App\Infrastructure\Http\User\Dto\CalculatePriceDto;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final readonly class CalculatePriceDenormalizer
{
    public function denormalize(Payload $payload): CalculatePriceDto
    {
        /**
         * @psalm-var array{
         *     product: int,
         *     taxNumber: string,
         *     couponCode: string|null
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

        return new CalculatePriceDto(
            productId: $arguments['product'],
            taxCode: $arguments['taxNumber'],
            couponCode: $arguments['couponCode'] ?? null,
        );
    }
}
