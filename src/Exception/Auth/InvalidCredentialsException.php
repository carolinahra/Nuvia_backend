<?php

namespace App\Exception\Auth;

class InvalidCredentialsException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('Invalid credentials', 401);
    }
}
