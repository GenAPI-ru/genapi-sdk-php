<?php

namespace GenAPI;

use GenAPI\Enums\Http\EndpointsEnum;
use GenAPI\Enums\Http\HttpMethods;
use GenAPI\Exceptions\BadRequestException;
use GenAPI\Exceptions\BaseException;
use GenAPI\Exceptions\InternalServerError;
use GenAPI\Exceptions\NotFoundException;
use GenAPI\Exceptions\StreamParameterNotSetException;
use GenAPI\Exceptions\TooManyRequestsException;
use GenAPI\Exceptions\UnauthorizedException;
use GenAPI\Http\Client\BaseClient;
use GenAPI\Http\Client\SseClient;
use JsonException;

class Client extends BaseClient
{
    /**
     * Get User Info.
     *
     * @return array|null
     * @throws BadRequestException
     * @throws BaseException
     * @throws InternalServerError
     * @throws JsonException
     * @throws NotFoundException
     * @throws TooManyRequestsException
     * @throws UnauthorizedException
     */
    public function getMe(): ?array
    {
        $response = $this->execute(EndpointsEnum::ME_PATH, HttpMethods::GET, []);

        return $response->isOk()
            ? $this->decodeData($response)
            : $this->handleError($response);
    }

    /**
     * Creating a network task.
     *
     * @param string $networkId - unique model identifier.
     * @param array $parameters - request parameters.
     * @return array|null
     * @throws BadRequestException
     * @throws BaseException
     * @throws InternalServerError
     * @throws JsonException
     * @throws NotFoundException
     * @throws TooManyRequestsException
     * @throws UnauthorizedException
     */
    public function createNetworkTask(string $networkId, array $parameters): ?array
    {
        $body = $this->encodeData($parameters);

        $response = $this->execute(EndpointsEnum::CREATE_NETWORK_TASK_PATH . '/' . $networkId, HttpMethods::POST, [], $body);

        return $response->isOk()
            ? $this->decodeData($response)
            : $this->handleError($response);
    }

    /**
     * Creating a stream task.
     *
     * Receiving a response in the form of SSE.
     * Only use in text neural networks with stream = true.
     *
     * @param string $networkId - unique model identifier.
     * @param array $parameters - request parameters.
     * @param callable $callback - a callback in which your event processing functionality is written.
     * @return bool
     * @throws BadRequestException
     * @throws BaseException
     * @throws InternalServerError
     * @throws JsonException
     * @throws NotFoundException
     * @throws StreamParameterNotSetException
     * @throws TooManyRequestsException
     * @throws UnauthorizedException
     */
    public function createStreamNetworkTask(string $networkId, array $parameters, callable $callback): bool
    {
        array_key_exists('stream', $parameters)
            ?: throw new StreamParameterNotSetException();

        $this->client = new SseClient($this->client);
        $body = $this->encodeData($parameters);

        $response = $this->execute(EndpointsEnum::CREATE_NETWORK_TASK_PATH . '/' . $networkId, HttpMethods::POST, [], $body, [], $callback);

        return $response->isOk()
            ?: $this->handleError($response);
    }

    /**
     * Get Request by ID.
     *
     * This request is needed to get the result by task ID.
     *
     * @param int $requestId - request ID issued after task creation.
     * @return array|null
     * @throws BadRequestException
     * @throws BaseException
     * @throws InternalServerError
     * @throws JsonException
     * @throws NotFoundException
     * @throws TooManyRequestsException
     * @throws UnauthorizedException
     */
    public function getRequest(int $requestId): ?array
    {
        $response = $this->execute(EndpointsEnum::GET_REQUEST_PATH . '/' . $requestId, HttpMethods::GET, []);

        return $response->isOk()
            ? $this->decodeData($response)
            : $this->handleError($response);
    }

    /**
     * Creating a function task.
     *
     * Create a task in the selected ai function.
     *
     * @param string $functionId - unique AI function identifier.
     * @param array $parameters - request parameters.
     * @return array|null
     * @throws BadRequestException
     * @throws BaseException
     * @throws InternalServerError
     * @throws JsonException
     * @throws NotFoundException
     * @throws TooManyRequestsException
     * @throws UnauthorizedException
     */
    public function createFunctionTask(string $functionId, array $parameters): ?array
    {
        $body = $this->encodeData($parameters);

        $response = $this->execute(EndpointsEnum::CREATE_FUNCTION_TASK_PATH . '/' . $functionId, HttpMethods::POST, [], $body);

        return $response->isOk()
            ? $this->decodeData($response)
            : $this->handleError($response);
    }
}
