<?php

declare(strict_types=1);

namespace App\Codechallenge\Billing\Application\Exceptions;

class CartIsEmptyException extends \Exception
{
    protected $message = 'The cart is empty';
}
