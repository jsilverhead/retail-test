<?php

namespace App\Infrastructure\Http\User\Action;

use App\Domain\Coupon\Service\CalculatePriceService;
use App\Infrastructure\Http\Payload\Payload;
use App\Infrastructure\Http\User\Denormalizer\CalculatePriceDenormalizer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route(path: '/calculate-price', methods: [Request::METHOD_POST])]
final readonly class CalculatePrice
{
    public function __construct(
        private CalculatePriceDenormalizer $calculatePriceDenormalizer,
        private CalculatePriceService $calculatePriceService,
        private ValidatorInterface $validator,
    ) {
    }

    public function __invoke(Payload $payload): Response
    {
        $dto = $this->calculatePriceDenormalizer->denormalize($payload);

        $violations = $this->validator->validate($dto);

        if ($violations->count() > 0) {
            $errors = [];

            foreach ($violations as $violation) {
                $errors[] = $violation->getMessage();
            }

            return new JsonResponse(['errors' => $errors], Response::HTTP_BAD_REQUEST);
        }

        $price = $this->calculatePriceService->calculate($dto);

        return new JsonResponse([
            'totalPrice' => [
                'euro' => $price->euro,
                'cent' => $price->cent,
            ],
        ]);
    }
}
