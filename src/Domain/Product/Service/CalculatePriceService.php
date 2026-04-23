<?php

namespace App\Domain\Product\Service;

use App\Domain\Coupon\Repository\CouponRepositoryInterface;
use App\Domain\Product\Repository\ProductRepositoryInterface;
use App\Domain\Product\ValueObject\Price;
use App\Infrastructure\Calculator\PriceCalculatorInterface;

final readonly class CalculatePriceService
{
    public function __construct(
        private CountryExtractorInterface $countryExtractor,
        private PriceCalculatorInterface $priceCalculator,
        private ProductRepositoryInterface $productRepository,
        private CouponRepositoryInterface $couponRepository,
    ) {
    }

    public function calculate(int $productId, string $taxCode, ?string $couponCode): Price
    {
        $country = $this->countryExtractor->extractCountry($taxCode);
        $product = $this->productRepository->getByIdOrFail($productId);
        $coupon = null;

        if (null !== $couponCode) {
            $coupon = $this->couponRepository->getCouponByCodeOrFail($couponCode);
        }

        return $this->priceCalculator->calculate(product: $product, coupon: $coupon, country: $country);
    }
}
