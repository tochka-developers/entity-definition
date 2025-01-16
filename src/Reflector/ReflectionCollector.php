<?php

declare(strict_types=1);

namespace Tochka\EntityDefinition\Reflector;

/**
 * @api
 */
class ReflectionCollector
{
    private static array $classReflections = [];
    private static array $methodReflection = [];
    private static array $propertyReflection = [];
    private static array $parameterReflection = [];

    /**
     * @param class-string|trait-string $className
     * @throws \ReflectionException
     */
    public static function getClassReflection(string $className): \ReflectionClass
    {
        if (!isset(self::$classReflections[$className])) {
            self::$classReflections[$className] = new \ReflectionClass($className);
        }

        return self::$classReflections[$className];
    }

    /**
     * @param class-string|trait-string $className
     * @throws \ReflectionException
     */
    public static function getMethodReflection(string $className, string $methodName): \ReflectionMethod
    {
        if (!isset(self::$methodReflection[$className][$methodName])) {
            self::$methodReflection[$className][$methodName] = new \ReflectionMethod($className, $methodName);
        }

        return self::$methodReflection[$className][$methodName];
    }

    /**
     * @param class-string|trait-string $className
     * @throws \ReflectionException
     */
    public static function getPropertyReflection(string $className, string $propertyName): \ReflectionProperty
    {
        if (!isset(self::$propertyReflection[$className][$propertyName])) {
            self::$propertyReflection[$className][$propertyName] = new \ReflectionProperty($className, $propertyName);
        }

        return self::$propertyReflection[$className][$propertyName];
    }

    /**
     * @param class-string|trait-string $className
     * @throws \ReflectionException
     */
    public static function getParameterReflection(string $className, string $methodName, string $parameterName): \ReflectionParameter
    {
        if (!isset(self::$parameterReflection[$className][$methodName])) {
            $parameters = self::getMethodReflection($className, $methodName)->getParameters();
            foreach ($parameters as $parameter) {
                self::$parameterReflection[$className][$methodName][$parameter->getName()] = $parameter;
            }
        }

        if (!isset(self::$parameterReflection[$className][$methodName][$parameterName])) {
            throw new \ReflectionException(
                sprintf('Parameter "%s" not found in method [%s::%s]', $parameterName, $className, $methodName),
            );
        }

        return self::$parameterReflection[$className][$methodName][$parameterName];
    }
}
