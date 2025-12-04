<?php

declare(strict_types=1);

namespace App\Codechallenge\Auth\Application\Exceptions;

class UserAlreadyExistException extends \Exception
{
    protected $message = 'The email is already registered';
}
