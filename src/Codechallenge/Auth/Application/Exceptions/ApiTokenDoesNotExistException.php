<?php

namespace App\Codechallenge\Auth\Application\Exceptions;

use Exception;

class ApiTokenDoesNotExistException extends Exception
{
  protected $message = 'The access token does not exist';
}