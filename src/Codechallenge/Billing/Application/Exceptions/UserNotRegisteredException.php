<?php

namespace App\Codechallenge\Billing\Application\Exceptions;

use Exception;

class UserNotRegisteredException extends Exception
{
  protected $message = 'The user is not registered';
}