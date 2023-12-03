<?php

namespace App\Codechallenge\Auth\Application\Exceptions;

class UserDoesNotExistException extends \Exception
{
    protected $message = 'The user does not exist';
}
