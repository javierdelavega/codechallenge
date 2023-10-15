<?php

namespace App\Codechallenge\Billing\Application\Exceptions;

use Exception;

class CartIsEmptyException extends Exception
{
  protected $message = 'The cart is empty';
}