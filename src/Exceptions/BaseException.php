<?php

namespace GenAPI\Exceptions;

use Exception;
use GenAPI\Enums\Http\HttpErrorsEnum;

class BaseException extends Exception
{
    /**
     * Response Body.
     *
     * @var string|null
     */
    protected ?string $responseBody = '';

    /**
     * Response Headers.
     *
     * @var array
     */
    protected array $responseHeaders = [];

    /**
     * ApiException constructor.
     *
     * @param string $message
     * @param int $code
     * @param array $headers
     * @param string|null $body
     */
    public function __construct(string $message = '', int $code = 0, array $headers = [], ?string $body = '')
    {
        parent::__construct($message, $code);

        $this->responseHeaders  = $headers;
        $this->responseBody     = $body;
    }

    /**
     * Response Body Getter.
     *
     * @return string|null
     */
    public function getResponseBody(): ?string
    {
        return $this->responseBody;
    }

    /**
     * Response Headers Getter.
     *
     * @return array
     */
    public function getResponseHeaders(): array
    {
        return $this->responseHeaders;
    }

    /**
     * Prepare Exception Message.
     *
     * @param string $body
     * @param int $errorCode
     * @return string
     */
    protected function prepareMessage(string $body, int $errorCode): string
    {
        $errorData  = json_decode($body, true);
        $message    = '';

        if (isset($errorData['error']) && is_string($errorData['error'])) {
            $message .= $errorData['error'];
        } else {
            $message = HttpErrorsEnum::getMessage($errorCode);
        }

        if (isset($errorData['code'])) {
            $message .= sprintf('Error code: %s. ', $errorData['code']);
        }

        if (isset($errorData['parameter'])) {
            $message .= sprintf('Parameter name: %s. ', $errorData['parameter']);
        }

        return trim($message);
    }
}
