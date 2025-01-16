<?php

declare(strict_types=1);

namespace Tochka\EntityDefinition\TypeReflector;

use Tochka\Types\TypeInterface;

/**
 * @api
 */
interface VariableTypeReflectorInterface
{
    /**
     * @template TValueType
     * @param TValueType $variable
     * @return TypeInterface<TValueType>
     */
    public function getVariableType(mixed $variable, bool $atomic = false): TypeInterface;
}
