<?php

namespace GenAPI\Configs;

use GenAPI\Contracts\Config\ConfigurationLoaderContract;

class ConfigurationLoader implements ConfigurationLoaderContract
{
    /**
     * Config parameters.
     *
     * @var array
     */
    protected array $parameters;

    /**
     * Load Curl configuration.
     *
     * @param string|null $filePath
     * @return $this
     */
    public function load(string $filePath = null): self
    {
        if ($filePath) {
            $data = file_get_contents($filePath);
        } else {
            $data = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'configuration.json');
        }

        $paramsArray = json_decode($data, true);

        $this->parameters = $paramsArray;

        return $this;
    }

    /**
     * Config getter.
     *
     * @return array
     */
    public function getConfig(): array
    {
        return $this->parameters;
    }
}
