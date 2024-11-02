<?php

namespace GenAPI\Exceptions;

use Exception;

class StreamParameterNotSetException extends Exception
{
    /**
     * CallbackNotSetException constructor.
     *
     * @param int $code
     */
    public function __construct(int $code = 0)
    {
        parent::__construct('stream parameter is not set!', $code);
    }
}
