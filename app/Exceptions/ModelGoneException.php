<?php

namespace App\Exceptions;

use Exception;

class ModelGoneException extends Exception
{
    public $status = 410;

    public function errors()
    {
        return [[
            $this->message 
                ? $this->message 
                : __('model.genericGone')
        ]];
    }
}
