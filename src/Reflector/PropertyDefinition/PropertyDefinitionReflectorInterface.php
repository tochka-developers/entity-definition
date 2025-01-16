<?php

declare(strict_types=1);

namespace Tochka\EntityDefinition\Reflector\PropertyDefinition;

use Tochka\EntityDefinition\Definition\PropertyDefinition;

/**
 * @api
 */
interface PropertyDefinitionReflectorInterface
{
    public function getPropertyDefinition(\ReflectionProperty $reflectionProperty): PropertyDefinition;
}
