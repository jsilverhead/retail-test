<?php

namespace App\Infrastructure\EventListener;

use App\Infrastructure\Exception\EntityNotFoundException;
use App\Infrastructure\Exception\ServiceException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Exception\ValidationFailedException;

final readonly class DomainExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof ServiceException) {
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

        if ($exception instanceof ValidationFailedException) {
            $errors = [];

            foreach ($exception->getViolations() as $violation) {
                $errors[] = $violation->getMessage();
            }

            $event->setResponse(
                new JsonResponse(
                    [
                        'type' => 'validation_failed',
                        'description' => 'Validation failed',
                        'errors' => $errors,
                    ],
                    Response::HTTP_BAD_REQUEST,
                ),
            );
        }

        if ($exception instanceof BadRequestHttpException) {
            $event->setResponse(
                new JsonResponse(
                    [
                        'type' => 'bad_request',
                        'description' => $exception->getMessage(),
                    ],
                    Response::HTTP_BAD_REQUEST,
                ),
            );
        }
    }
}
