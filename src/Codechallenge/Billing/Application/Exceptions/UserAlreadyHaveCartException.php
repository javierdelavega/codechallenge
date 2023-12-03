<?php

namespace App\Codechallenge\Billing\Application\Exceptions;

class UserAlreadyHaveCartException extends \Exception
{
    protected $message = 'The user already hace a cart';
}
