<?php

namespace GenAPI\Http\Responses;

class ResponseObject
{
    /**
     * HTTP Code.
     *
     * @var int
     */
    protected int $code;

    /**
     * Request Headers.
     *
     * @var array
     */
    protected array $headers;

    /**
     * Request Body.
     *
     * @var string
     */
    protected string $body;

    /**
     * ResponseObject constructor.
     *
     * @param array|null $config
     */
    public function __construct(array $config = null)
    {
        if (isset($config['headers'])) {
            $this->headers = $config['headers'];
        }

        if (isset($config['body'])) {
            $this->body = $config['body'];
        }

        if (isset($config['code'])) {
            $this->code = (int) $config['code'];
        }
    }

    /**
     * Response Headers Getter.
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Response Body Getter.
     *
     * @return mixed
     */
    public function getBody(): mixed
    {
        return $this->body;
    }

    /**
     * Response Code Getter.
     *
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * Checking if the response code is successful.
     *
     * @return bool
     */
    public function isOk(): bool
    {
        return $this->code === 200;
    }
}
