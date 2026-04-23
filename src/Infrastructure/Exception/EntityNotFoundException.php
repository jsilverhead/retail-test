<?php

namespace App\Infrastructure\Exception;

use App\Infrastructure\Exception\Enum\EntityNotFoundEnum;

final class EntityNotFoundException extends ServiceException
{
    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function __construct(public readonly EntityNotFoundEnum $entity)
    {
        parent::__construct();
    }

    public function getDescription(): string
    {
        return \sprintf("Entity '%s' not found", $this->entity->value);
    }

    public function getType(): string
    {
        return 'entity_not_found';
    }
}
