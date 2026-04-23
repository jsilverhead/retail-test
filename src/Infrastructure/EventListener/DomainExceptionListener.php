<?php

namespace App\Infrastructure\EventListener;

use App\Infrastructure\Exception\EntityNotFoundException;
use App\Infrastructure\Exception\ServiceException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

final readonly class DomainExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if (!$exception instanceof ServiceException) {
            return;
        }

        $statusCode = match (true) {
            $exception instanceof EntityNotFoundException => Response::HTTP_NOT_FOUND,
            default => Response::HTTP_BAD_REQUEST,
        };

        $response = new JsonResponse(
            [
                'type' => $exception->getType(),
                'description' => $exception->getDescription(),
            ],
            $statusCode,
        );

        $event->setResponse($response);
    }
}
