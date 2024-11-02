<?php

namespace GenAPI\Exceptions;

class UnprocessableEntityException extends BaseException
{
    /**
     * Exception HTTP code.
     */
    public const HTTP_CODE = 422;

    /**
     * BadRequestException constructor.
     *
     * @param array $headers
     * @param string|null $body
     */
    public function __construct(array $headers = [], ?string $body = '')
    {
        parent::__construct($this->prepareMessage($body, self::HTTP_CODE), self::HTTP_CODE, $headers, $body);
    }
}
