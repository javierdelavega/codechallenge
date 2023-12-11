<?php

declare(strict_types=1);

namespace App\Codechallenge\Auth\Application\Exceptions;

class UserAlreadyRegisteredException extends \Exception
{
    protected $message = 'The user is already registered';
}
