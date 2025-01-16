<?php

declare(strict_types=1);

namespace Tochka\EntityDefinition\Reflector\Support;

use Tochka\EntityDefinition\Collection\AttributeCollection;

trait AttributesTrait
{
    private function getAttributes(\Reflector $reflector): AttributeCollection
    {
        $attributes = [];

        if (
            $reflector instanceof \ReflectionMethod
            || $reflector instanceof \ReflectionParameter
            || $reflector instanceof \ReflectionProperty
            || $reflector instanceof \ReflectionClass
        ) {
            $attributes = array_map(
                fn(\ReflectionAttribute $reflectionAttribute) => $reflectionAttribute->newInstance(),
                $reflector->getAttributes(),
            );
        }

        return new AttributeCollection($attributes);
    }
}
