<?php

namespace App\Infrastructure\Http\Payload;

/**
 * @psalm-suppress PossiblyUnusedProperty
 */
final readonly class Payload
{
    /**
     * @psalm-param array $arguments
     */
    public function __construct(public array $arguments)
    {
    }
}
