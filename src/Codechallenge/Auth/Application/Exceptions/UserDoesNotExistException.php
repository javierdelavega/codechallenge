<?php

namespace App\Codechallenge\Auth\Application\Exceptions;

use Exception;

class UserDoesNotExistException extends Exception
{
  protected $message = 'The user does not exist';
}