<?php

namespace GenAPI\Contracts\Config;

interface ConfigurationLoaderContract
{
    /**
     * Load configuration.
     *
     * @return ConfigurationLoaderContract
     */
    public function load(): ConfigurationLoaderContract;

    /**
     * Config getter.
     *
     * @return array
     */
    public function getConfig(): array;
}
