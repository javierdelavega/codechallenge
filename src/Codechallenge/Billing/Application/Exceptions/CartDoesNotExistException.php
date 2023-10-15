<?php

namespace App\Codechallenge\Billing\Application\Exceptions;

use Exception;

class CartDoesNotExistException extends Exception
{
  protected $message = 'The cart does not exist';
}