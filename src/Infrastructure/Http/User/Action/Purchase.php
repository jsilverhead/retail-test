<?php

namespace App\Infrastructure\Http\User\Action;

use App\Domain\Ware\Service\PurchaseService;
use App\Infrastructure\Http\Payload\Payload;
use App\Infrastructure\Http\User\Denormalizer\PurchaseDenormalizer;
use App\Infrastructure\Http\User\Dto\PurchaseDto;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route(path: '/purchase', methods: [Request::METHOD_POST])]
final readonly class Purchase
{
    public function __construct(
        private PurchaseDenormalizer $purchaseDenormalizer,
        private ValidatorInterface $validator,
        private PurchaseService $purchaseService,
    ) {
    }

    private function validateDto(PurchaseDto $dto): void
    {
        $violations = $this->validator->validate($dto);

        if ($violations->count() > 0) {
            throw new ValidationFailedException($dto, $violations);
        }
    }

    public function __invoke(Payload $payload): Response
    {
        $dto = $this->purchaseDenormalizer->denormalize($payload);

        $this->validateDto($dto);

        $this->purchaseService->purchase(
            productId: $dto->productId,
            taxCode: $dto->taxCode,
            couponCode: $dto->couponCode,
            processor: $dto->paymentProcessor,
        );

        return new JsonResponse();
    }
}
