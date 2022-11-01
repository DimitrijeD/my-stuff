<?php

namespace App\Exceptions;

use Exception;

class UnAuthrorizedException extends Exception
{
    public $status = 403;

    public function errors()
    {
        return [[
            $this->message 
                ? $this->message 
                : __('auth.genericUnAuthorized')
        ]];
    }
}
