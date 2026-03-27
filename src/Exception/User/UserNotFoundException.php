<?php

namespace App\Exception\User;

class UserNotFoundException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('User not found', 404);
    }
}
