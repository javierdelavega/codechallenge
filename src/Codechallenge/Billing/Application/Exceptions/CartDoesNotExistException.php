<?php

declare(strict_types=1);

namespace App\Codechallenge\Billing\Application\Exceptions;

class CartDoesNotExistException extends \Exception
{
    protected $message = 'The cart does not exist';
}
