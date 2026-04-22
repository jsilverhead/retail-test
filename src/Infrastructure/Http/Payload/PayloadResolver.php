<?php

namespace App\Infrastructure\Http\Payload;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

final readonly class PayloadResolver implements ValueResolverInterface
{
    public function __construct(private PayloadDeserializer $deserializer)
    {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if (Payload::class !== $argument->getType()) {
            return [];
        }

        yield $this->deserializer->deserialize($request);
    }
}
