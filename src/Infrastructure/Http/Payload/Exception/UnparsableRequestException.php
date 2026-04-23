<?php

namespace App\Infrastructure\Http\Payload\Exception;

use App\Infrastructure\Exception\ServiceException;

final class UnparsableRequestException extends ServiceException
{
    public function getDescription(): string
    {
        return 'Unparsable request';
    }

    public function getType(): string
    {
        return 'unparsable_request';
    }
}
