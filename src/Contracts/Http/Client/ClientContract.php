<?php

namespace GenAPI\Contracts\Http\Client;

use GenAPI\Http\Responses\ResponseObject;

interface ClientContract
{
    /**
     * Creating a Curl request and receiving a processed response.
     *
     * @param string $path
     * @param string $method
     * @param array $queryParameters
     * @param string|null $httpBody
     * @param array $headers
     * @return ResponseObject
     */
    public function call(string $path, string $method, array $queryParameters, ?string $httpBody = null, array $headers = []): ResponseObject;

    /**
     * Bearer Token Setter.
     *
     * @param string $bearerToken
     * @return void
     */
    public function setBearerToken(string $bearerToken): void;

    /**
     * Config Setter.
     *
     * @param array $config
     * @return void
     */
    public function setConfig(array $config): void;
}
