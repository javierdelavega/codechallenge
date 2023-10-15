<?php

namespace App\Codechallenge\Billing\Application\Exceptions;

use Exception;

class UserAlreadyHaveCartException extends Exception
{
  protected $message = 'The user already hace a cart';
}