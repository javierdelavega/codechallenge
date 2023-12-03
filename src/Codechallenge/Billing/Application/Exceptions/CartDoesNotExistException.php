<?php

namespace App\Codechallenge\Billing\Application\Exceptions;

class CartDoesNotExistException extends \Exception
{
    protected $message = 'The cart does not exist';
}
