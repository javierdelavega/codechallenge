<?php

namespace App\Codechallenge\Catalog\Application\Exceptions;

class ProductDoesNotExistException extends \Exception
{
    protected $message = 'The product does not exist';
}
