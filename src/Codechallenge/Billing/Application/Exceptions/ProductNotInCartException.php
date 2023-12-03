<?php

namespace App\Codechallenge\Billing\Application\Exceptions;

class ProductNotInCartException extends \Exception
{
    protected $message = 'The product is not in the cart';
}
