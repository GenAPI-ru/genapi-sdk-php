<?php

namespace GenAPI\Http\Clients;

use CurlHandle;
use GenAPI\Contracts\Http\Client\ClientContract;
use GenAPI\Enums\Http\HttpMethods;
use GenAPI\Exceptions\AuthorizeException;
use GenAPI\Exceptions\CallbackNotSetException;
use GenAPI\Exceptions\ConnectionException;
use GenAPI\Exceptions\ExtensionNotFoundException;
use GenAPI\Http\Responses\ResponseObject;

class SseClient extends CurlClient implements ClientContract
{
    /**
     * Default Request Headers.
     *
     * @var array
     */
    protected array $defaultHeaders = [
        'Content-Type'  => 'application/json',
        'Accept'        => 'text/event-stream',
    ];

    /**
     * Curl Timeout.
     *
     * @var int
     */
    protected int $timeout = 0;

    /**
     * SseClient constructor.
     *
     * @param CurlClient $curlClient
     */
    public function __construct(CurlClient $curlClient)
    {
        $this->config       = $curlClient->getConfig();
        $this->bearerToken  = $curlClient->getBearerToken();
    }

    /**
     * Chunk Buffer.
     *
     * @var string
     */
    protected string $buffer = '';

    /**
     * Curl Connection Timeout.
     *
     * @var int
     */
    protected int $connectionTimeout = 100;

    /**
     * Execute the SSE request.
     *
     * @param string $path
     * @param string $method
     * @param array $queryParameters
     * @param string|null $httpBody
     * @param array $headers
     * @param callable|null $callback
     * @return ResponseObject
     * @throws AuthorizeException
     * @throws CallbackNotSetException
     * @throws ConnectionException
     * @throws ExtensionNotFoundException
     */
    public function call(string $path, string $method, array $queryParameters, ?string $httpBody = null, array $headers = [], ?callable $callback = null): ResponseObject
    {
        $headers = $this->prepareHeaders($headers);

        $url = $this->prepareUrl($path, $queryParameters);

        $this->prepareSseCurl($method, $url, $httpBody, $this->implodeHeaders($headers), $callback);

        [$httpHeaders, $httpBody, $responseInfo] = $this->sendRequest();

        return new ResponseObject([
            'code'      => $responseInfo['http_code'],
            'headers'   => $httpHeaders,
            'body'      => $httpBody,
        ]);
    }

    /**
     * Prepare Curl Instance for SSE.
     *
     * @param string $method
     * @param string $url
     * @param string|null $httpBody
     * @param array $headers
     * @param callable|null $callback
     * @return void
     * @throws CallbackNotSetException
     * @throws ExtensionNotFoundException
     */
    protected function prepareSseCurl(string $method, string $url, ?string $httpBody = null, array $headers = [], ?callable $callback = null): void
    {
        if (! $callback) {
            throw new CallbackNotSetException();
        }

        $this->initCurl();

        $this->setCurlOption(CURLOPT_URL, $url);

        if ($method === HttpMethods::POST) {
            $this->setCurlOption(CURLOPT_POST, true);
            $this->setBody($method, $httpBody);
        } else {
            $this->setCurlOption(CURLOPT_HTTPGET, true);
        }

        $this->setCurlOption(CURLOPT_RETURNTRANSFER, false);
        $this->setCurlOption(CURLOPT_HTTPHEADER, $headers);
        $this->setCurlOption(CURLOPT_TIMEOUT, $this->timeout);

        $this->setCurlOption(CURLOPT_WRITEFUNCTION, function (CurlHandle $handle, string $data) use ($callback) {
            return $this->processSseData($data, $callback);
        });
    }

    /**
     * Process Events.
     *
     * @param string $data
     * @param callable $callback
     * @return int
     */
    protected function processSseData(string $data, callable $callback): int
    {
        $this->buffer .= $data;

        $start = 0;

        while (($end = strpos($this->buffer, "\n\n", $start)) !== false) {
            $chunk = substr($this->buffer, $start, $end - $start);

            $trimmedChunk = trim($chunk);

            if (! empty($trimmedChunk)) {
                call_user_func($callback, $trimmedChunk);
            }

            $start = $end + 2;
        }

        $this->buffer = substr($this->buffer, $start);

        return strlen($data);
    }
}
