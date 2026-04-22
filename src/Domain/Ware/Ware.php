<?php

namespace App\Domain\Ware;

use App\Domain\Ware\ValueObject\Price;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
final class Ware
{
    /** @psalm-suppress PropertyNotSetInConstructor */
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\Column(type: Types::STRING, length: 255, unique: true)]
    private string $name;

    #[ORM\Column(type: 'price')]
    private Price $price;

    public function __construct(string $name, Price $price)
    {
        $this->name = $name;
        $this->price = $price;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): Price
    {
        return $this->price;
    }
}
