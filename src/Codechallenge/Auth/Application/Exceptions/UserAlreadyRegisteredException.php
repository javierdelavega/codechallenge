<?php

namespace App\Codechallenge\Auth\Application\Exceptions;

class UserAlreadyRegisteredException extends \Exception
{
    protected $message = 'The user is already registered';
}
