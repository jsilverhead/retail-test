<?php

namespace App\Tests\Functional\Infrastructure\Http\Action;

use App\Domain\Coupon\Enum\CodeTypeEnum;
use App\Domain\Product\ValueObject\Price;
use App\Infrastructure\Http\User\Action\CalculatePrice;
use App\Tests\Builder\CouponBuilder;
use App\Tests\Builder\ProductBuilder;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 */
#[CoversClass(CalculatePrice::class)]
final class CalculatePriceTest extends WebTestCase
{
    public function testSuccess(): void
    {
        $client = self::createClient();

        $productBuilder = $this->getContainer()->get(ProductBuilder::class);
        $couponBuilder = $this->getContainer()->get(CouponBuilder::class);

        $product = $productBuilder->withPrice(new Price(euro: 100, cent: 0))->withName('Test Iphone')->build();
        $coupon = $couponBuilder->withType(CodeTypeEnum::PERCENTAGE)->withPercentage(6)->build();

        $request = [
            'product' => $product->getId(),
            'taxNumber' => 'GR123456789',
            'couponCode' => $coupon->getCode(),
        ];

        $client->request(
            method: Request::METHOD_POST,
            uri: '/calculate-price',
            content: json_encode($request, \JSON_THROW_ON_ERROR),
        );

        $this->assertResponseIsSuccessful();

        $responseContent = $client->getResponse()->getContent();
        self::assertNotFalse($responseContent);

        /** @psalm-var array{totalPrice: array{euro: int, cent: int}} $responseData */
        $responseData = json_decode($responseContent, true, 512, \JSON_THROW_ON_ERROR);

        self::assertTrue(isset($responseData['totalPrice']));
        self::assertTrue(isset($responseData['totalPrice']['euro']));
        self::assertTrue(isset($responseData['totalPrice']['cent']));

        $euro = $responseData['totalPrice']['euro'];
        $cent = $responseData['totalPrice']['cent'];

        self::assertSame(116, $euro);
        self::assertSame(56, $cent);
    }
}
