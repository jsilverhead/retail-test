<?php

namespace App\Domain\Ware\Exception;

use App\Infrastructure\Exception\ServiceException;

final class ProcessPaymentFailedException extends ServiceException
{
    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function getDescription(): string
    {
        return 'Process payment failed';
    }

    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function getType(): string
    {
        return 'process_payment_failed';
    }
}
