<?php

namespace App\Domain\Coupon\Service;

use App\Domain\Coupon\Repository\CouponRepositoryInterface;
use App\Domain\Ware\Repository\WareRepositoryInterface;
use App\Domain\Ware\ValueObject\Price;
use App\Infrastructure\Calculator\PriceCalculatorInterface;
use App\Infrastructure\Http\User\Dto\CalculatePriceDto;
use App\Infrastructure\Validator\CountryExtractorInterface;

final readonly class CalculatePriceService
{
    public function __construct(
        private CountryExtractorInterface $countryExtractor,
        private PriceCalculatorInterface $priceCalculator,
        private WareRepositoryInterface $wareRepository,
        private CouponRepositoryInterface $couponRepository,
    ) {
    }

    public function calculate(CalculatePriceDto $dto): Price
    {
        $country = $this->countryExtractor->extractCountry($dto->taxCode);
        $ware = $this->wareRepository->getByIdOrFail($dto->productId);
        $coupon = null;

        if (null !== $dto->couponCode) {
            $coupon = $this->couponRepository->getCouponByCodeOrFail($dto->couponCode);
        }

        return $this->priceCalculator->calculate(ware: $ware, coupon: $coupon, country: $country);
    }
}
