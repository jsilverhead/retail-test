<?php

namespace App\Infrastructure\Console;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

#[AsCommand(name: 'app:init-test:data', description: 'Init test data')]
final class InitTestDataCommand extends Command
{
    public function __construct(private readonly Connection $connection)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $this->connection->beginTransaction();

            $this->connection->executeStatement('TRUNCATE TABLE product RESTART IDENTITY CASCADE');
            $this->connection->executeStatement('TRUNCATE TABLE coupon RESTART IDENTITY CASCADE');

            $this->connection->executeStatement('INSERT INTO product (id, name, price) VALUES (:id, :name, :price)', [
                'id' => 1,
                'name' => 'Iphone',
                'price' => json_encode(['euro' => 100, 'cent' => 0], \JSON_THROW_ON_ERROR),
            ]);

            $this->connection->executeStatement('INSERT INTO product (id, name, price) VALUES (:id, :name, :price)', [
                'id' => 2,
                'name' => 'Наушники',
                'price' => json_encode(['euro' => 20, 'cent' => 0], \JSON_THROW_ON_ERROR),
            ]);

            $this->connection->executeStatement('INSERT INTO product (id, name, price) VALUES (:id, :name, :price)', [
                'id' => 3,
                'name' => 'Чехол',
                'price' => json_encode(['euro' => 10, 'cent' => 0], \JSON_THROW_ON_ERROR),
            ]);

            $this->connection->executeStatement(
                'INSERT INTO coupon (id, code, fixed_value, percentage, type) VALUES (:id, :code, :fixed_value, :percentage, :type)',
                [
                    'id' => 1,
                    'code' => 'D15',
                    'fixed_value' => 15,
                    'percentage' => null,
                    'type' => 'fixed_value',
                ],
            );

            $this->connection->executeStatement(
                'INSERT INTO coupon (id, code, fixed_value, percentage, type) VALUES (:id, :code, :fixed_value, :percentage, :type)',
                [
                    'id' => 2,
                    'code' => 'NEW6',
                    'fixed_value' => null,
                    'percentage' => 6,
                    'type' => 'percentage',
                ],
            );

            $this->connection->executeStatement("SELECT setval('product_id_seq', (SELECT MAX(id) FROM product))");
            $this->connection->executeStatement("SELECT setval('coupon_id_seq', (SELECT MAX(id) FROM coupon))");

            $this->connection->commit();
        } catch (Throwable $exception) {
            $this->connection->rollBack();
            $io->error('Failed to initialize test data: ' . $exception->getMessage());

            return Command::FAILURE;
        }

        $io->success('Test data initialized');

        return Command::SUCCESS;
    }
}
