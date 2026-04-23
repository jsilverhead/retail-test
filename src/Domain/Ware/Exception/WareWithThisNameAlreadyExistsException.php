<?php

namespace App\Domain\Ware\Exception;

use App\Infrastructure\Exception\ServiceException;

final class WareWithThisNameAlreadyExistsException extends ServiceException
{
    public function getDescription(): string
    {
        return 'Ware with the same name already exists';
    }

    public function getType(): string
    {
        return 'ware_with_this_name_already_exists';
    }
}
