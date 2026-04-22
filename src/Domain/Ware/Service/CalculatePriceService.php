<?php

namespace App\Domain\Ware\Service;

use App\Domain\Coupon\Repository\CouponRepositoryInterface;
use App\Domain\Ware\Repository\WareRepositoryInterface;
use App\Domain\Ware\ValueObject\Price;
use App\Infrastructure\Calculator\PriceCalculatorInterface;

final readonly class CalculatePriceService
{
    public function __construct(
        private CountryExtractorInterface $countryExtractor,
        private PriceCalculatorInterface $priceCalculator,
        private WareRepositoryInterface $wareRepository,
        private CouponRepositoryInterface $couponRepository,
    ) {
    }

    public function calculate(int $productId, string $taxCode, ?string $couponCode): Price
    {
        $country = $this->countryExtractor->extractCountry($taxCode);
        $ware = $this->wareRepository->getByIdOrFail($productId);
        $coupon = null;

        if (null !== $couponCode) {
            $coupon = $this->couponRepository->getCouponByCodeOrFail($couponCode);
        }

        return $this->priceCalculator->calculate(ware: $ware, coupon: $coupon, country: $country);
    }
}
