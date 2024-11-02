<?php

namespace Tests\GenAPI\Unit\Config;

use GenAPI\Configs\ConfigurationLoader;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ConfigurationLoaderTest extends TestCase
{
    /**
     * Test load method.
     *
     * @param string|null $fileName
     * @return void
     */
    #[Test, DataProvider('validDataProvider')]
    public function testLoad(?string $fileName): void
    {
        $loader = new ConfigurationLoader();

        if (empty($fileName)) {
            $fileName = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'configuration.json';
        }

        $loader->load($fileName);

        $data = file_get_contents($fileName);

        self::assertEquals(
            json_decode($data, true),
            $loader->getConfig()
        );
    }

    /**
     * Data provider for testLoad.
     *
     * @return array
     */
    public static function validDataProvider(): array
    {
        return [
            [null],
            [''],
            [__DIR__ . DIRECTORY_SEPARATOR . 'test_configuration.json'],
        ];
    }
}
