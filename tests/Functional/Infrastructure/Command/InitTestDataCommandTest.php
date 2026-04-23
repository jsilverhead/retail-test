<?php

namespace App\Tests\Functional\Infrastructure\Command;

use App\Infrastructure\Console\InitTestDataCommand;
use Doctrine\DBAL\Connection;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @internal
 */
#[CoversClass(InitTestDataCommand::class)]
final class InitTestDataCommandTest extends WebTestCase
{
    private Connection $connection;

    public function testSuccess(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('app:init-test:data');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $commandTester->assertCommandIsSuccessful();

        $productCount = (int) $this->connection->fetchOne('SELECT COUNT(DISTINCT id) FROM product');
        $couponCount = (int) $this->connection->fetchOne('SELECT COUNT(DISTINCT id) FROM coupon');

        self::assertSame(expected: 4, actual: $productCount);
        self::assertSame(expected: 2, actual: $couponCount);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->connection = self::getContainer()->get(Connection::class);
    }
}
