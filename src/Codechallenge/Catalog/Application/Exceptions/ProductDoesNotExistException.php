<?php

namespace App\Codechallenge\Catalog\Application\Exceptions;

use Exception;

class ProductDoesNotExistException extends Exception
{
  protected $message = 'The product does not exist';
}