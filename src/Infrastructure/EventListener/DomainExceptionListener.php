<?php

namespace App\Infrastructure\EventListener;

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

        $response = new JsonResponse(
            [
                'type' => $exception->getType(),
                'description' => $exception->getDescription(),
            ],
            Response::HTTP_BAD_REQUEST,
        );

        $event->setResponse($response);
    }
}
