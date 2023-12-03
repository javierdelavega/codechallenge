<?php

namespace App\Codechallenge\Billing\Application\Exceptions;

class UserNotRegisteredException extends \Exception
{
    protected $message = 'The user is not registered';
}
