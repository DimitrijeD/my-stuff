<?php

namespace App\Exceptions;

use Exception;

class InternalServerErrorException extends Exception
{
    public $status = 500;

    public function errors()
    {
        return [[
            $this->message 
                ? $this->message 
                : __('serverError.failed')
        ]];
    }
}
