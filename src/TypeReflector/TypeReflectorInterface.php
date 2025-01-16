<?php

declare(strict_types=1);

namespace Tochka\EntityDefinition\TypeReflector;

use Tochka\Types\TypeInterface;

/**
 * @api
 */
interface TypeReflectorInterface
{
    public function getType(?\ReflectionType $reflectionType, ?TypeInterface $docBlockType = null): TypeInterface;
}
