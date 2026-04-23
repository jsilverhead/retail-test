<?php

namespace App\Infrastructure\Exception;

use DomainException;

abstract class ServiceException extends DomainException
{
    abstract public function getDescription(): string;

    abstract public function getType(): string;
}
