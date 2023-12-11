<?php

declare(strict_types=1);

namespace App\Codechallenge\Auth\Application\Exceptions;

class ApiTokenDoesNotExistException extends \Exception
{
    protected $message = 'The access token does not exist';
}
