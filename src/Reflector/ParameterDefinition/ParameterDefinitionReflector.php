<?php

declare(strict_types=1);

namespace Tochka\EntityDefinition\Reflector\ParameterDefinition;

use JetBrains\PhpStorm\Deprecated;
use Tochka\DocBlockParser\Tags\ParamTag;
use Tochka\EntityDefinition\Collection\AttributeCollection;
use Tochka\EntityDefinition\Definition\Flag\ParameterFlag;
use Tochka\EntityDefinition\Definition\ParameterDefinition;
use Tochka\EntityDefinition\Reflector\Support\AttributesTrait;
use Tochka\EntityDefinition\TypeReflector\TypeReflectorInterface;

readonly class ParameterDefinitionReflector implements ParameterDefinitionReflectorInterface
{
    use AttributesTrait;

    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function __construct(
        private TypeReflectorInterface $typeReflector,
    ) {}

    public function getParameterDefinition(
        \ReflectionParameter $reflectionParameter,
        ?ParamTag $docBlockParam = null,
    ): ParameterDefinition {
        $attributes = $this->getAttributes($reflectionParameter);

        return new ParameterDefinition(
            type: $this->typeReflector->getType($reflectionParameter->getType(), $docBlockParam?->type),
            name: $reflectionParameter->getName(),
            modifiers: $this->getModifiers($reflectionParameter, $attributes),
            hasDefaultValue: $reflectionParameter->isDefaultValueAvailable(),
            defaultValue: $reflectionParameter->isDefaultValueAvailable()
                ? $reflectionParameter->getDefaultValue()
                : null,
            attributes: $attributes,
            description: $docBlockParam?->description,
        );
    }

    /**
     * @return int-mask-of<ParameterFlag::*>
     */
    private function getModifiers(\ReflectionParameter $reflection, AttributeCollection $attributes): int
    {
        $modifiers = 0;
        if ($reflection->isVariadic()) {
            $modifiers |= ParameterFlag::IS_VARIADIC;
        }
        if ($reflection->isPromoted()) {
            $modifiers |= ParameterFlag::IS_PROMOTED;
        }
        if ($attributes->hasByType(Deprecated::class)) {
            $modifiers |= ParameterFlag::IS_DEPRECATED;
        }

        return $modifiers;
    }
}
