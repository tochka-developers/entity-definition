<?php

declare(strict_types=1);

namespace Tochka\EntityDefinition\Reflector\ParameterDefinition;

use Tochka\DocBlockParser\Tags\ParamTag;
use Tochka\EntityDefinition\Definition\ParameterDefinition;

/**
 * @api
 */
interface ParameterDefinitionReflectorInterface
{
    public function getParameterDefinition(
        \ReflectionParameter $reflectionParameter,
        ?ParamTag $docBlockParam = null,
    ): ParameterDefinition;
}
