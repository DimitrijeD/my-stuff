<?php

namespace App\Exceptions;

use Exception;

class UnAuthenticatedException extends Exception
{
    public $status = 401;

    public function errors()
    {
        return [[
            $this->message 
                ? $this->message 
                : __('auth.mustBeLoggedIn')
        ]];
    }
}
