<?php

namespace GenAPI\Exceptions;

use Exception;

class CallbackNotSetException extends Exception
{
    /**
     * CallbackNotSetException constructor.
     *
     * @param int $code
     */
    public function __construct(int $code = 0)
    {
        parent::__construct('callback not set!', $code);
    }
}
