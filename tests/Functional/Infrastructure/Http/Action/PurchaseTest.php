<?php

namespace App\Tests\Functional\Infrastructure\Http\Action;

use App\Domain\Coupon\Enum\CodeTypeEnum;
use App\Domain\Ware\ValueObject\Price;
use App\Infrastructure\Http\User\Action\Purchase;
use App\Tests\Builder\CouponBuilder;
use App\Tests\Builder\WareBuilder;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

/**
 * @internal
 */
#[CoversClass(Purchase::class)]
final class PurchaseTest extends WebTestCase
{
    public function testSuccess(): void
    {
        $client = self::createClient();

        $wareBuilder = $this->getContainer()->get(WareBuilder::class);
        $couponBuilder = $this->getContainer()->get(CouponBuilder::class);

        $ware = $wareBuilder->withPrice(new Price(euro: 100, cent: 0))->withName('iPhone')->build();
        $coupon = $couponBuilder->withType(CodeTypeEnum::PERCENTAGE)->withPercentage(6)->build();

        $request = [
            'product' => $ware->getId(),
            'taxNumber' => 'GR123456789',
            'couponCode' => $coupon->getCode(),
            'paymentProcessor' => 'paypal',
        ];

        $client->request(
            method: Request::METHOD_POST,
            uri: '/purchase',
            content: json_encode($request, \JSON_THROW_ON_ERROR),
        );

        $this->assertResponseIsSuccessful();
    }
}
