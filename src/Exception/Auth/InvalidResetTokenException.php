<?php

namespace App\Exception\Auth;

class InvalidResetTokenException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('Invalid or expired reset token');
    }
}
