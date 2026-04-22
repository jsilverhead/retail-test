<?php

namespace App\Infrastructure\Http\Payload\Exception;

use App\Infrastructure\Exception\ServiceException;

final class UnparsableRequestException extends ServiceException
{
    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function getDescription(): string
    {
        return 'Unparsable request';
    }

    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function getType(): string
    {
        return 'unparsable_request';
    }
}
