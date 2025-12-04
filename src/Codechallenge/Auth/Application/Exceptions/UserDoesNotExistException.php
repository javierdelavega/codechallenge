<?php

declare(strict_types=1);

namespace App\Codechallenge\Auth\Application\Exceptions;

class UserDoesNotExistException extends \Exception
{
    protected $message = 'The user does not exist';
}
