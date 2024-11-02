<?php

namespace Tests\GenAPI\Unit\Http\Clients;

use GenAPI\Configs\ConfigurationLoader;
use GenAPI\Contracts\Config\ConfigurationLoaderContract;
use GenAPI\Contracts\Http\Client\ClientContract;
use GenAPI\Http\Clients\BaseClient;
use GenAPI\Http\Clients\CurlClient;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\GenAPI\TestCase;

class BaseClientTest extends TestCase
{
    /**
     * Test setAuthToken method.
     *
     * @param ClientContract|null $client
     * @param ConfigurationLoaderContract|null $configLoader
     * @return void
     */
    #[Test, DataProvider('initialDataProvider')]
    public function testSetAuthToken(?ClientContract $client, ?ConfigurationLoaderContract $configLoader): void
    {
        $instance = self::getInstance($client, $configLoader);
        $instance->setAuthToken('token');
        self::assertTrue($instance->getClient() instanceof ClientContract);
    }

    /**
     * Test setClient method.
     *
     * @param array $value
     * @return void
     */
    #[Test, DataProvider('configurationDataProvider')]
    public function testSetClient(array $value): void
    {
        $instance = self::getInstance();

        $client = new CurlClient();
        $client->setConnectionTimeout($value['connectionTimeout']);
        $client->setTimeout($value['timeout']);
        $client->setConfig($value['config']);
        $client->setBearerToken($value['bearer']);

        $instance->setClient($client);
        self::assertEquals($client->getConfig(), $instance->getConfig());
        self::assertTrue($instance->getClient() instanceof ClientContract);
    }

    /**
     * Test getConfig and setConfig methods.
     *
     * @param ClientContract|null $client
     * @param ConfigurationLoaderContract|null $configLoader
     * @return void
     */
    #[Test, DataProvider('initialDataProvider')]
    public function testGetSetConfig(?ClientContract $client, ?ConfigurationLoaderContract $configLoader): void
    {
        $config = ['url' => 'test:url'];

        $instance = self::getInstance($client, $configLoader);
        $instance->setConfig($config);

        $client = new CurlClient();
        $client->setConfig($config);
        self::assertEquals($client->getConfig(), $instance->getConfig());
    }

    /**
     * Initial Data Provider.
     *
     * @return array
     */
    public static function initialDataProvider(): array
    {
        return [
            [
                'client'        => null,
                'configLoader'  => null,
            ],
            [
                'client'        => new CurlClient(),
                'configLoader'  => new ConfigurationLoader(),
            ],
        ];
    }

    /**
     * Configuration Data Provider.
     *
     * @return array
     */
    public static function configurationDataProvider(): array
    {
        return [
            [
                [
                    'connectionTimeout'     => 23,
                    'timeout'               => 1,
                    'config'                => ['url' => 'https://api.gen-api.ru'],
                    'bearer'                => 'auth_token',
                ],
            ],
            [
                [
                    'connectionTimeout'     => 30,
                    'timeout'               => 60,
                    'config'                => [],
                    'bearer'                => 'bearer',
                ],
            ],
        ];
    }

    /**
     * Get instance.
     *
     * @param ClientContract|null $client
     * @param ConfigurationLoaderContract|null $configLoader
     * @return BaseClient
     */
    protected static function getInstance(?ClientContract $client = null, ?ConfigurationLoaderContract $configLoader = null): BaseClient
    {
        return new BaseClient($client, $configLoader);
    }
}
