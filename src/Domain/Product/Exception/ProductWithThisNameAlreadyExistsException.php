<?php

namespace App\Domain\Product\Exception;

use App\Infrastructure\Exception\ServiceException;

final class ProductWithThisNameAlreadyExistsException extends ServiceException
{
    public function getDescription(): string
    {
        return 'Product with the same name already exists';
    }

    public function getType(): string
    {
        return 'product_with_this_name_already_exists';
    }
}
