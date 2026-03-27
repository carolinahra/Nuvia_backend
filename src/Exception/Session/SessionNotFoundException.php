<?php

namespace App\Exception\Session;

class SessionNotFoundException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('Session not found', 404);
    }
}
