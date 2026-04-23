<?php

namespace App\Domain\Ware\Exception;

use App\Infrastructure\Exception\ServiceException;

final class ProcessPaymentFailedException extends ServiceException
{
    public function __construct(string $message = 'Process payment failed')
    {
        parent::__construct($message);
    }

    public function getDescription(): string
    {
        return $this->message;
    }

    public function getType(): string
    {
        return 'process_payment_failed';
    }
}
