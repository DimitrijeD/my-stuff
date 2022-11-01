<?php

namespace App\Exceptions;

use Exception;

class ModelDoesntExistException extends Exception
{
    public $status = 404;

    public function errors()
    {
        return [[
            $this->message 
                ? $this->message 
                : __('model.genericCannotFind')
        ]];
    }
}
