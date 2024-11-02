<?php

namespace Tests\GenAPI\Unit\Http\Clients;

use GenAPI\Enums\Http\HttpMethods;
use GenAPI\Exceptions\AuthorizeException;
use GenAPI\Exceptions\ConnectionException;
use GenAPI\Exceptions\ExtensionNotFoundException;
use GenAPI\Http\Clients\CurlClient;
use PHPUnit\Framework\Attributes\Test;
use ReflectionException;
use Tests\GenAPI\TestCase;

class CurlClientTest extends TestCase
{
    /**
     * Test setConfig and getConfig methods.
     *
     * @return void
     */
    #[Test]
    public function testSetConfig(): void
    {
        $data = ['test' => true];
        $client = new CurlClient();
        $client->setConfig($data);
        $this->assertEquals($data, $client->getConfig());
    }

    /**
     * Test setBearerToken and getBearerToken methods.
     *
     * @return void
     */
    #[Test]
    public function testGetBearerToken(): void
    {
        $data = 'bearerToken';
        $client = new CurlClient();
        $client->setBearerToken($data);
        $this->assertEquals($data, $client->getBearerToken());
    }

    /**
     * Test getUrl method.
     *
     * @return void
     * @throws ReflectionException
     */
    #[Test]
    public function testGetUrl(): void
    {
        $data = ['url' => 'https://test.test'];
        $client = new CurlClient();
        $client->setConfig($data);

        $method = $this->getAccessibleMethod(CurlClient::class, 'getUrl');
        $this->assertEquals($data['url'], $method->invoke($client));
    }

    /**
     * Test setKeepAlive methods.
     *
     * @return void
     * @throws ReflectionException
     */
    #[Test]
    public function testSetKeepAlive(): void
    {
        $client = new CurlClient();
        $client->setKeepAlive(true);
        $property = $this->getAccessibleProperty(CurlClient::class, 'keepAlive');
        $this->assertTrue($property->getValue($client));
    }

    /**
     * Test close curl connection.
     *
     * @return void
     * @throws AuthorizeException
     * @throws ConnectionException
     * @throws ExtensionNotFoundException
     */
    #[Test]
    public function testCloseConnection(): void
    {
        $curlClientMock = $this->getMockBuilder(CurlClient::class)
            ->onlyMethods(['closeCurlConnection', 'sendRequest'])
            ->getMock();

        $curlClientMock->setConfig(['url' => 'url']);
        $curlClientMock->setKeepAlive(false);
        $curlClientMock->setBearerToken('bearerToken');

        $curlClientMock->expects($this->once())->method('sendRequest')->willReturn([
            ['Header' => 'value'],
            '{body:test}',
            ['code' => 200],
        ]);

        $curlClientMock->expects($this->once())->method('closeCurlConnection');

        $curlClientMock->call(
            '',
            HttpMethods::GET,
            ['query' => 'value'],
            'body',
            ['header' => 'value']
        );
    }

    /**
     * Test authorization exception.
     *
     * @return void
     * @throws AuthorizeException
     * @throws ConnectionException
     * @throws ExtensionNotFoundException
     */
    #[Test]
    public function testAuthorizeException(): void
    {
        $this->expectException(AuthorizeException::class);

        $client = new CurlClient();

        $client->call(
            '',
            HttpMethods::GET,
            ['query' => 'value'],
            'body',
            ['header' => 'value']
        );
    }
}
