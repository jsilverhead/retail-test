<?php

namespace App\Domain\Coupon;

use App\Domain\Coupon\Enum\CodeTypeEnum;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
final class Coupon
{
    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $code;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $fixedValue;

    /** @psalm-suppress PropertyNotSetInConstructor */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private int $id;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $percentage;

    #[ORM\Column(type: Types::STRING, enumType: CodeTypeEnum::class)]
    private CodeTypeEnum $type;

    public function __construct(string $code, CodeTypeEnum $type, ?int $percentage = null, ?int $fixedValue = null)
    {
        $this->code = $code;
        $this->type = $type;
        $this->percentage = $percentage;
        $this->fixedValue = $fixedValue;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getFixedValue(): ?int
    {
        return $this->fixedValue;
    }

    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function getId(): int
    {
        return $this->id;
    }

    public function getPercentage(): ?int
    {
        return $this->percentage;
    }

    public function getType(): CodeTypeEnum
    {
        return $this->type;
    }
}
