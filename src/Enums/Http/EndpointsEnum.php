<?php

namespace GenAPI\Enums\Http;

enum EndpointsEnum
{
    /**
     * Get User Endpoint Path.
     */
    public const string ME_PATH = '/api/v1/user';

    /**
     * Network Endpoint Path.
     */
    public const string CREATE_NETWORK_TASK_PATH = '/api/v1/networks';

    /**
     * Function Endpoint Path.
     */
    public const string CREATE_FUNCTION_TASK_PATH = '/api/v1/functions';

    /**
     * Get Request Endpoint Path.
     */
    public const string GET_REQUEST_PATH = '/api/v1/request/get';
}
