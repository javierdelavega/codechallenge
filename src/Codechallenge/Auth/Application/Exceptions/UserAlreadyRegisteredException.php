<?php

namespace App\Codechallenge\Auth\Application\Exceptions;

use Exception;

class UserAlreadyRegisteredException extends Exception
{
  protected $message = 'The user is already registered';
}