<?php

namespace GenAPI\Exceptions;

class TooManyRequestsException extends BaseException
{
    /**
     * Exception HTTP code.
     */
    public const int HTTP_CODE = 429;

    /**
     * TooManyRequestsException constructor.
     *
     * @param array $headers
     * @param string|null $body
     */
    public function __construct(array $headers = [], ?string $body = '')
    {
        parent::__construct($this->prepareMessage($body, self::HTTP_CODE), self::HTTP_CODE, $headers, $body);
    }
}
