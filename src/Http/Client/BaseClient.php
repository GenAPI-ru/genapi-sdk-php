<?php

namespace GenAPI\Http\Client;

use GenAPI\Config\ConfigurationLoader;
use GenAPI\Contracts\Config\ConfigurationLoaderContract;
use GenAPI\Contracts\Http\Client\ClientContract;
use GenAPI\Enums\Http\HttpErrorsEnum;
use GenAPI\Exceptions\BadRequestException;
use GenAPI\Exceptions\BaseException;
use GenAPI\Exceptions\InternalServerError;
use GenAPI\Exceptions\MethodNotAllowedException;
use GenAPI\Exceptions\NotFoundException;
use GenAPI\Exceptions\TooManyRequestsException;
use GenAPI\Exceptions\UnauthorizedException;
use GenAPI\Exceptions\UnprocessableEntityException;
use GenAPI\Http\Responses\ResponseObject;
use JsonException;

class BaseClient
{
    /**
     * Curl Client.
     *
     * @var ClientContract|null
     */
    protected ?ClientContract $client;

    /**
     * Curl Configuration.
     *
     * @var array
     */
    protected array $config = [];

    /**
     * BaseClient constructor.
     *
     * @param ClientContract|null $client
     * @param ConfigurationLoaderContract|null $configurator
     */
    public function __construct(ClientContract $client = null, ConfigurationLoaderContract $configurator = null)
    {
        if ($client === null) {
            $client = new CurlClient();
        }

        if ($configurator === null) {
            $configurator = new ConfigurationLoader();
        }

        $config = $configurator->load()->getConfig();
        $this->setConfig($config);

        $client->setConfig($config);
        $this->setClient($client);
    }

    /**
     * Curl Config Getter.
     *
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * Curl Config Setter.
     *
     * @param array $config
     * @return void
     */
    public function setConfig(array $config): void
    {
        $this->config = $config;
    }

    /**
     * Set Curl Client.
     *
     * @param ClientContract $client
     * @return void
     */
    public function setClient(ClientContract $client): void
    {
        $this->client = $client;
    }

    /**
     * Auth Token Setter.
     *
     * @param string $token
     * @return $this
     */
    public function setAuthToken(string $token): self
    {
        $this->client->setBearerToken($token);

        return $this;
    }

    /**
     * Decode Data.
     *
     * @param ResponseObject $response
     * @return array
     * @throws JsonException
     */
    protected function decodeData(ResponseObject $response): array
    {
        return json_decode($response->getBody(), true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * Encode Data.
     *
     * @param array $serializedData
     * @return string
     * @throws JsonException
     */
    protected function encodeData(array $serializedData): string
    {
        if ($serializedData === []) {
            return '{}';
        }

        return json_encode($serializedData, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Handle Error.
     *
     * @param ResponseObject $response
     * @return void
     * @throws BadRequestException
     * @throws BaseException
     * @throws InternalServerError
     * @throws NotFoundException
     * @throws TooManyRequestsException
     * @throws UnauthorizedException
     */
    protected function handleError(ResponseObject $response): void
    {
        match ($response->getCode()) {
            NotFoundException::HTTP_CODE            => throw new NotFoundException($response->getHeaders(), $response->getBody()),
            InternalServerError::HTTP_CODE          => throw new InternalServerError($response->getHeaders(), $response->getBody()),
            BadRequestException::HTTP_CODE          => throw new BadRequestException($response->getHeaders(), $response->getBody()),
            UnauthorizedException::HTTP_CODE        => throw new UnauthorizedException($response->getHeaders(), $response->getBody()),
            TooManyRequestsException::HTTP_CODE     => throw new TooManyRequestsException($response->getHeaders(), $response->getBody()),
            MethodNotAllowedException::HTTP_CODE    => throw new MethodNotAllowedException($response->getHeaders(), $response->getBody()),
            UnprocessableEntityException::HTTP_CODE => throw new UnprocessableEntityException($response->getHeaders(), $response->getBody()),
            default                                 => throw new BaseException(HttpErrorsEnum::UNKNOWN_ERROR, $response->getCode(), $response->getHeaders(), $response->getBody())
        };
    }

    /**
     * Execute request.
     *
     * @param string $path
     * @param string $method
     * @param array $queryParameters
     * @param string|null $httpBody
     * @param array $headers
     * @param callable|null $callback
     * @return ResponseObject
     */
    protected function execute(string $path, string $method, array $queryParameters, ?string $httpBody = null, array $headers = [], callable $callback = null): ResponseObject
    {
        return $this->client->call($path, $method, $queryParameters, $httpBody, $headers, $callback);
    }
}
