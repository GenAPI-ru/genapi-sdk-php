<?php

namespace Tests\GenAPI\Unit\Exceptions;

use GenAPI\Exceptions\BaseException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\GenAPI\TestCase;

class BaseExceptionTest extends TestCase
{
    /**
     * Get Test Instance.
     *
     * @param string $message
     * @param int $code
     * @param array $headers
     * @param string|null $body
     * @return BaseException
     */
    public function getInstance(string $message = '', int $code = 0, array $headers = [], ?string $body = ''): BaseException
    {
        return new BaseException($message, $code, $headers, $body);
    }

    /**
     * Test getResponseHeaders method.
     *
     * @param array $headers
     * @return void
     */
    #[Test, DataProvider('responseHeadersProvider')]
    public function testGetResponseHeaders(array $headers): void
    {
        $instance = $this->getInstance(headers: $headers);
        self::assertEquals($headers, $instance->getResponseHeaders());
    }

    /**
     * Response Headers Provider.
     *
     * @return array
     */
    public static function responseHeadersProvider(): array
    {
        return [
            [
                [],
            ],
            [
                ['HTTP/1.1 200 OK'],
            ],
            [
                [
                    'HTTP/1.1 200 OK',
                    'Content-length: 0',
                ],
            ],
            [
                [
                    'HTTP/1.1 200 OK',
                    'Content-length: 0',
                    'Connection: close',
                ],
            ],
        ];
    }

    /**
     * Test getResponseBody method.
     *
     * @param string $body
     * @return void
     */
    #[Test, DataProvider('responseBodyProvider')]
    public function testGetResponseBody(string $body): void
    {
        $instance = $this->getInstance(body: $body);
        self::assertEquals($body, $instance->getResponseBody());
    }

    /**
     * Response Body Provider.
     *
     * @return array
     */
    public static function responseBodyProvider(): array
    {
        return [
            [
                '',
            ],
            [
                '{"ok":true,"test:false"}',
            ],
            [
                '<html></html>',
            ],
        ];
    }
}