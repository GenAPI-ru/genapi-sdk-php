<?php

namespace Tests\GenAPI;

use PHPUnit\Framework\TestCase as BaseTestCase;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionProperty;

abstract class TestCase extends BaseTestCase
{
    /**
     * Get Accessible Method.
     *
     * @param string $class
     * @param string $methodName
     * @return ReflectionMethod
     * @throws ReflectionException
     */
    protected function getAccessibleMethod(string $class, string $methodName): ReflectionMethod
    {
        $reflection = new ReflectionClass($class);

        $method = $reflection->getMethod($methodName);

        $method->setAccessible(true);

        return $method;
    }

    /**
     * Get Accessible Property.
     *
     * @param string $class
     * @param string $propertyName
     * @return ReflectionProperty
     * @throws ReflectionException
     */
    protected function getAccessibleProperty(string $class, string $propertyName): ReflectionProperty
    {
        $reflection = new ReflectionClass($class);

        $property = $reflection->getProperty($propertyName);

        $property->setAccessible(true);

        return $property;
    }
}
