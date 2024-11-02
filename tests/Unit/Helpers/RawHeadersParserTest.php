<?php

namespace Tests\GenAPI\Unit\Helpers;

use GenAPI\Helpers\RawHeadersParser;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class RawHeadersParserTest extends TestCase
{
    /**
     * Test parse method with empty headers.
     *
     * @return void
     */
    #[Test]
    public function testParseEmptyHeaders(): void
    {
        $result = RawHeadersParser::parse('');
        self::assertSame([], $result);
    }

    /**
     * Test parse method with a single header.
     *
     * @return void
     */
    #[Test]
    public function testParseSingleHeader(): void
    {
        $rawHeaders = "Content-Type: application/json";
        $expected = [
            'Content-Type' => 'application/json'
        ];

        $result = RawHeadersParser::parse($rawHeaders);
        self::assertSame($expected, $result);
    }

    /**
     * Test parse method with multiple headers.
     *
     * @return void
     */
    #[Test]
    public function testParseMultipleHeaders(): void
    {
        $rawHeaders = "Content-Type: application/json\nAuthorization: Bearer token";
        $expected = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer token'
        ];

        $result = RawHeadersParser::parse($rawHeaders);
        self::assertSame($expected, $result);
    }

    /**
     * Test parse method with multiline header.
     *
     * @return void
     */
    #[Test]
    public function testParseMultilineHeader(): void
    {
        $rawHeaders = "Set-Cookie: sessionId=abc123;\n Set-Cookie: userId=xyz789;";
        $expected = [
            'Set-Cookie' => [
                'sessionId=abc123;',
                'userId=xyz789;'
            ]
        ];

        $result = RawHeadersParser::parse($rawHeaders);
        self::assertSame($expected, $result);
    }

    /**
     * Test parse method with a header containing continuation lines.
     *
     * @return void
     */
    #[Test]
    public function testParseHeaderWithContinuation(): void
    {
        $rawHeaders = "Array-Header: value1\r\n" .
            "Array-Header: value2\r\n" .
            "Array-Header: value3\r\n";
        $expected = [
            'Array-Header' => [
                'value1', 'value2', 'value3'
            ],
        ];

        $result = RawHeadersParser::parse($rawHeaders);
        self::assertSame($expected, $result);
    }

    /**
     * Test parse method with a malformed header.
     *
     * @return void
     */
    #[Test]
    public function testParseMalformedHeader(): void
    {
        $rawHeaders = "MalformedHeaderWithoutColon";
        $expected = [];

        $result = RawHeadersParser::parse($rawHeaders);
        self::assertSame($expected, $result);
    }

    /**
     * Test parse method with headers containing empty lines.
     *
     * @return void
     */
    #[Test]
    public function testParseHeadersWithEmptyLines(): void
    {
        $rawHeaders = "Content-Type: application/json\n\nAuthorization: Bearer token\n\n";
        $expected = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer token'
        ];

        $result = RawHeadersParser::parse($rawHeaders);
        self::assertSame($expected, $result);
    }
}
