<?php

declare(strict_types=1);

namespace Tochka\EntityDefinition;

use Tochka\EntityDefinition\Reflector\ClassDefinition\ClassDefinitionReflectorFactory;
use Tochka\EntityDefinition\Reflector\ClassDefinition\ClassDefinitionReflectorInterface;
use Tochka\EntityDefinition\Reflector\MethodDefinition\MethodDefinitionReflectorFactory;
use Tochka\EntityDefinition\Reflector\MethodDefinition\MethodDefinitionReflectorInterface;
use Tochka\EntityDefinition\Reflector\ParameterDefinition\ParameterDefinitionReflectorFactory;
use Tochka\EntityDefinition\Reflector\ParameterDefinition\ParameterDefinitionReflectorInterface;
use Tochka\EntityDefinition\Reflector\PropertyDefinition\PropertyDefinitionReflectorFactory;
use Tochka\EntityDefinition\Reflector\PropertyDefinition\PropertyDefinitionReflectorInterface;
use Tochka\EntityDefinition\TypeReflector\TypeReflector;
use Tochka\EntityDefinition\TypeReflector\TypeReflectorInterface;
use Tochka\EntityDefinition\TypeReflector\VariableTypeReflector;
use Tochka\EntityDefinition\TypeReflector\VariableTypeReflectorInterface;

/**
 * @api
 */
class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                ClassDefinitionReflectorInterface::class => ClassDefinitionReflectorFactory::class,
                MethodDefinitionReflectorInterface::class => MethodDefinitionReflectorFactory::class,
                PropertyDefinitionReflectorInterface::class => PropertyDefinitionReflectorFactory::class,
                ParameterDefinitionReflectorInterface::class => ParameterDefinitionReflectorFactory::class,
                TypeReflectorInterface::class => TypeReflector::class,
                VariableTypeReflectorInterface::class => VariableTypeReflector::class,
            ],
        ];
    }
}
