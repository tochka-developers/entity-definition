<?php

declare(strict_types=1);

namespace Tochka\EntityDefinition\Reflector\ParameterDefinition;

use Tochka\DocBlockParser\Tags\ParamTag;
use Tochka\EntityDefinition\Definition\ParameterDefinition;

class ParameterDefinitionCacheableReflector implements ParameterDefinitionReflectorInterface
{
    /** @var array<string, ParameterDefinition> */
    private array $parameterDefinitions = [];

    public function __construct(
        private readonly ParameterDefinitionReflectorInterface $reflector,
    ) {}

    public function getParameterDefinition(
        \ReflectionParameter $reflectionParameter,
        ?ParamTag $docBlockParam = null,
    ): ParameterDefinition {
        $cacheKey = $this->getCacheKey($reflectionParameter);

        if (!array_key_exists($cacheKey, $this->parameterDefinitions)) {
            $this->parameterDefinitions[$cacheKey] = $this->reflector->getParameterDefinition(
                $reflectionParameter,
                $docBlockParam,
            );
        }

        return $this->parameterDefinitions[$cacheKey];
    }

    private function getCacheKey(\ReflectionParameter $reflectionParameter): string
    {
        return sprintf(
            '%s::%s@%s',
            $reflectionParameter->getDeclaringClass()?->getName() ?? '<anonymous>',
            $reflectionParameter->getDeclaringFunction()->getName(),
            $reflectionParameter->getName(),
        );
    }
}
