<?php

namespace GenAPI\Enums\Http;

enum HttpErrorsEnum: string
{
    /**
     * Not Found HTTP Error.
     */
    public const HTTP_NOT_FOUND = 'Resource not found. Check the path is correct.';

    /**
     * Bad Request HTTP Error.
     */
    public const HTTP_BAD_REQUEST = 'Invalid request.';

    /**
     * Unauthorized HTTP Error.
     */
    public const HTTP_UNAUTHORIZED = 'Unauthorized. Check Auth token.';

    /**
     * Internal HTTP Error.
     */
    public const HTTP_INTERNAL_SERVER_ERROR = 'Internal Error. Try again later or write to technical support.';

    /**
     * Too Many Requests HTTP Error.
     */
    public const HTTP_TOO_MANY_REQUESTS = 'Too many requests, slow down.';

    /**
     * Unprocessable Entity HTTP Error.
     */
    public const HTTP_UNPROCESSABLE_ENTITY = 'Validation error, check request parameters.';

    /**
     * Method Not Allowed HTTP Error.
     */
    public const HTTP_METHOD_NOT_ALLOWED = 'Invalid method for this endpoint.';

    /**
     * Unknown HTTP Error.
     */
    public const UNKNOWN_ERROR = 'Unknown error.';

    /**
     * Related error message based on HTTP code.
     *
     * @param int $code
     * @return string
     */
    public static function getMessage(int $code): string
    {
        return match ($code) {
            400         => self::HTTP_BAD_REQUEST,
            401         => self::HTTP_UNAUTHORIZED,
            404         => self::HTTP_NOT_FOUND,
            405         => self::HTTP_METHOD_NOT_ALLOWED,
            422         => self::HTTP_UNPROCESSABLE_ENTITY,
            429         => self::HTTP_TOO_MANY_REQUESTS,
            500         => self::HTTP_INTERNAL_SERVER_ERROR,
            default     => self::UNKNOWN_ERROR,
        };
    }
}
