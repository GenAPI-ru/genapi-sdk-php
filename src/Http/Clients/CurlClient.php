<?php

namespace GenAPI\Http\Clients;

use CurlHandle;
use GenAPI\Contracts\Http\Client\ClientContract;
use GenAPI\Exceptions\AuthorizeException;
use GenAPI\Exceptions\ConnectionException;
use GenAPI\Exceptions\ExtensionNotFoundException;
use GenAPI\Helpers\RawHeadersParser;
use GenAPI\Http\Responses\ResponseObject;

class CurlClient implements ClientContract
{
    /**
     * Client Configuration.
     *
     * @var array
     */
    protected array $config = [];

    /**
     * Bearer Token.
     *
     * @var string|null
     */
    protected ?string $bearerToken = null;

    /**
     * Default Request Headers.
     *
     * @var array
     */
    protected array $defaultHeaders = [
        'Content-Type'  => 'application/json',
        'Accept'        => 'application/json',
    ];

    /**
     * Connection hold parameter.
     *
     * @var bool
     */
    protected bool $keepAlive = true;

    /**
     * Curl Instance.
     *
     * @var CurlHandle|null
     */
    protected ?CurlHandle $curl = null;

    /**
     * Curl Timeout.
     *
     * @var int
     */
    protected int $timeout = 100;

    /**
     * Curl Connection Timeout.
     *
     * @var int
     */
    protected int $connectionTimeout = 30;

    /**
     * Client Config Getter.
     *
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * Client Config Setter.
     *
     * @param array $config
     * @return void
     */
    public function setConfig(array $config): void
    {
        $this->config = $config;
    }

    /**
     * Bearer Token Setter.
     *
     * @param string|null $bearerToken
     * @return void
     */
    public function setBearerToken(?string $bearerToken): void
    {
        $this->bearerToken = $bearerToken;
    }

    /**
     * Bearer Token Getter.
     *
     * @return string
     */
    public function getBearerToken(): string
    {
        return $this->bearerToken;
    }

    /**
     * ConnectionTimeout Getter.
     *
     * @return int
     */
    public function getConnectionTimeout(): int
    {
        return $this->connectionTimeout;
    }

    /**
     * ConnectionTimeout Setter.
     *
     * @param int $connectionTimeout
     * @return void
     */
    public function setConnectionTimeout(int $connectionTimeout): void
    {
        $this->connectionTimeout = $connectionTimeout;
    }

    /**
     * Timeout Getter.
     *
     * @return int
     */
    public function getTimeout(): int
    {
        return $this->timeout;
    }

    /**
     * Timeout Setter.
     *
     * @param int $timeout
     * @return void
     */
    public function setTimeout(int $timeout): void
    {
        $this->timeout = $timeout;
    }

    /**
     * Get URL from Config.
     *
     * @return string
     */
    private function getUrl(): string
    {
        $config = $this->config;

        return (string) $config['url'];
    }

    /**
     * Keep Alive Setter.
     *
     * @param bool $keepAlive
     * @return $this
     */
    public function setKeepAlive(bool $keepAlive): self
    {
        $this->keepAlive = $keepAlive;

        return $this;
    }

    /**
     * Creating a Curl request and receiving a processed response.
     *
     * @param string $path
     * @param string $method
     * @param array $queryParameters
     * @param string|null $httpBody
     * @param array $headers
     * @return ResponseObject
     * @throws AuthorizeException
     * @throws ConnectionException
     * @throws ExtensionNotFoundException
     */
    public function call(string $path, string $method, array $queryParameters, ?string $httpBody = null, array $headers = []): ResponseObject
    {
        $headers = $this->prepareHeaders($headers);

        $url = $this->prepareUrl($path, $queryParameters);

        $this->prepareCurl($method, $url, $httpBody, $this->implodeHeaders($headers));

        [$httpHeaders, $httpBody, $responseInfo] = $this->sendRequest();

        if (! $this->keepAlive) {
            $this->closeCurlConnection();
        }

        return new ResponseObject([
            'code'      => $responseInfo['http_code'],
            'headers'   => $httpHeaders,
            'body'      => $httpBody,
        ]);
    }

    /**
     * Prepare Request Headers.
     *
     * @param array $headers
     * @return array
     * @throws AuthorizeException
     */
    protected function prepareHeaders(array $headers): array
    {
        $headers = array_merge($this->defaultHeaders, $headers);

        if ($this->bearerToken) {
            $headers['Authorization'] = 'Bearer ' . $this->bearerToken;
        }

        if (empty($headers['Authorization'])) {
            throw new AuthorizeException('Authorization header not set');
        }

        return $headers;
    }

    /**
     * Implode Request Headers.
     *
     * @param array $headers
     * @return array
     */
    protected function implodeHeaders(array $headers): array
    {
        return array_map(static fn ($key, $value) => $key . ':' . $value, array_keys($headers), $headers);
    }

    /**
     * Prepare URL.
     *
     * @param string $path
     * @param array $queryParams
     * @return string
     */
    protected function prepareUrl(string $path, array $queryParams): string
    {
        $url = $this->getUrl() . $path;

        if (! empty($queryParams)) {
            $url .= '?' . http_build_query($queryParams);
        }

        return $url;
    }

    /**
     * Prepare Curl Instance.
     *
     * @param string $method
     * @param string $url
     * @param string|null $httpBody
     * @param array $headers
     * @return void
     * @throws ExtensionNotFoundException
     */
    protected function prepareCurl(string $method, string $url, ?string $httpBody = null, array $headers = []): void
    {
        $this->initCurl();

        $this->setCurlOption(CURLOPT_URL, $url);

        $this->setCurlOption(CURLOPT_RETURNTRANSFER, true);

        $this->setCurlOption(CURLOPT_HEADER, true);

        $this->setBody($method, $httpBody);

        $this->setCurlOption(CURLOPT_HTTPHEADER, $headers);

        $this->setCurlOption(CURLOPT_CONNECTTIMEOUT, $this->connectionTimeout);

        $this->setCurlOption(CURLOPT_TIMEOUT, $this->timeout);
    }

    /**
     * Initialization Curl.
     *
     * @return void
     * @throws ExtensionNotFoundException
     */
    protected function initCurl(): void
    {
        if (! extension_loaded('curl')) {
            throw new ExtensionNotFoundException('curl');
        }

        if (! $this->curl || ! $this->keepAlive) {
            $this->curl = curl_init();
        }
    }

    /**
     * Set Curl Option.
     *
     * @param string $optionName
     * @param mixed $optionValue
     * @return bool
     */
    public function setCurlOption(string $optionName, mixed $optionValue): bool
    {
        return curl_setopt($this->curl, $optionName, $optionValue);
    }

    /**
     * Set Request Body.
     *
     * @param string $method
     * @param string|null $httpBody
     * @return void
     */
    public function setBody(string $method, ?string $httpBody = null): void
    {
        $this->setCurlOption(CURLOPT_CUSTOMREQUEST, $method);

        if (! empty($httpBody)) {
            $this->setCurlOption(CURLOPT_POSTFIELDS, $httpBody);
        }
    }

    /**
     * Send Request.
     *
     * @return array
     * @throws ConnectionException
     */
    public function sendRequest(): array
    {
        $response       = curl_exec($this->curl);
        $httpHeaderSize = curl_getinfo($this->curl, CURLINFO_HEADER_SIZE);
        $httpHeaders    = RawHeadersParser::parse(substr($response, 0, $httpHeaderSize));
        $httpBody       = substr($response, $httpHeaderSize);
        $responseInfo   = curl_getinfo($this->curl);
        $curlError      = curl_error($this->curl);
        $curlErrno      = curl_errno($this->curl);

        if ($response === false) {
            $this->handleCurlError($curlError, $curlErrno);
        }

        return [$httpHeaders, $httpBody, $responseInfo];
    }

    /**
     * Close Curl Connection.
     *
     * @return void
     */
    public function closeCurlConnection(): void
    {
        if ($this->curl !== null) {
            curl_close($this->curl);
        }
    }

    /**
     * Handle Curl Error.
     *
     * @param string $error
     * @param int $errno
     * @return void
     * @throws ConnectionException
     */
    private function handleCurlError(string $error, int $errno): void
    {
        $msg = match ($errno) {
            CURLE_COULDNT_CONNECT, CURLE_COULDNT_RESOLVE_HOST, CURLE_OPERATION_TIMEOUTED => 'Could not connect to GenAPI. Please check your internet connection and try again.',
            CURLE_SSL_CACERT, CURLE_SSL_PEER_CERTIFICATE => 'Could not verify SSL certificate.',
            default => 'Unexpected error communicating.',
        };

        $msg .= sprintf("\n\n(Network error [errno %s]: %s)", $errno, $error);

        throw new ConnectionException($msg);
    }
}
