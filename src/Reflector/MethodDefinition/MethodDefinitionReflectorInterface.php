<?php

declare(strict_types=1);

namespace Tochka\EntityDefinition\Reflector\MethodDefinition;

use Tochka\EntityDefinition\Definition\Collection\ParameterDefinitionCollectionInterface;
use Tochka\EntityDefinition\Definition\MethodDefinition;

/**
 * @api
 */
interface MethodDefinitionReflectorInterface
{
    public function getMethodDefinition(\ReflectionMethod $reflectionMethod): MethodDefinition;

    public function getParameters(\ReflectionMethod $reflectionMethod): ParameterDefinitionCollectionInterface;
}
