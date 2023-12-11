<?php

declare(strict_types=1);

namespace App\Codechallenge\Catalog\Application\Exceptions;

class ProductDoesNotExistException extends \Exception
{
    protected $message = 'The product does not exist';
}
