<?php

declare(strict_types=1);

namespace Tochka\EntityDefinition\Reflector\ClassDefinition;

use Tochka\EntityDefinition\Definition\ClassDefinition;
use Tochka\EntityDefinition\Definition\Collection\MethodDefinitionCollectionInterface;
use Tochka\EntityDefinition\Definition\Collection\PropertyDefinitionCollectionInterface;

/**
 * @api
 */
interface ClassDefinitionReflectorInterface
{
    /**
     * @template TClass of object
     * @param \ReflectionClass<TClass> $reflectionClass
     * @return ClassDefinition<TClass>
     */
    public function getClassDefinition(\ReflectionClass $reflectionClass): ClassDefinition;

    public function getProperties(\ReflectionClass $reflectionClass): PropertyDefinitionCollectionInterface;

    public function getMethods(\ReflectionClass $reflectionClass): MethodDefinitionCollectionInterface;
}
