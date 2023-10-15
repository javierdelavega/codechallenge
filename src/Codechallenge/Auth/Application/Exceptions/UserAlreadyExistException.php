<?php

namespace App\Codechallenge\Auth\Application\Exceptions;

use Exception;

class UserAlreadyExistException extends Exception
{
  protected $message = 'The email is already registered';
}