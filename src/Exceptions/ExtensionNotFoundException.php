<?php

namespace GenAPI\Exceptions;

use Exception;

class ExtensionNotFoundException extends Exception
{
    /**
     * ExtensionNotFoundException constructor.
     *
     * @param string $name
     * @param int $code
     */
    public function __construct(string $name, int $code = 0)
    {
        $message = sprintf('%s extension is not loaded!', $name);

        parent::__construct($message, $code);
    }
}
