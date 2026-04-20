<?php

namespace App\Infrastructure\Exception;

use DomainException;

abstract class ServiceException extends DomainException
{
    /**
     * @psalm-return non-empty-string
     *
     * @psalm-suppress PossiblyUnusedMethod
     */
    abstract public function getDescription(): string;

    /**
     * @psalm-return non-empty-string
     *
     * @psalm-suppress PossiblyUnusedMethod
     */
    abstract public function getType(): string;
}
