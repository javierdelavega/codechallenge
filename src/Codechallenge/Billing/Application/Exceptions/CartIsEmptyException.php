<?php

namespace App\Codechallenge\Billing\Application\Exceptions;

class CartIsEmptyException extends \Exception
{
    protected $message = 'The cart is empty';
}
