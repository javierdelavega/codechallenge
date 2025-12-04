<?php

declare(strict_types=1);

namespace App\Codechallenge\Billing\Application\Exceptions;

class UserNotRegisteredException extends \Exception
{
    protected $message = 'The user is not registered';
}
