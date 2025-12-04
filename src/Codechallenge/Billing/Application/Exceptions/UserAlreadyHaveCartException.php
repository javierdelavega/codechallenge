<?php

declare(strict_types=1);

namespace App\Codechallenge\Billing\Application\Exceptions;

class UserAlreadyHaveCartException extends \Exception
{
    protected $message = 'The user already hace a cart';
}
