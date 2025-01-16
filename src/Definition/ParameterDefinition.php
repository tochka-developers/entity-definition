<?php

declare(strict_types=1);

namespace Tochka\EntityDefinition\Definition;

use Tochka\EntityDefinition\Collection\AttributeCollection;
use Tochka\EntityDefinition\Definition\Flag\ParameterFlag;
use Tochka\Types\TypeInterface;

/**
 * @api
 */
readonly class ParameterDefinition extends ValueDefinition
{
    /**
     * @param int-mask-of<ParameterFlag::*> $modifiers
     */
    public function __construct(
        TypeInterface $type,
        public string $name,
        public int $modifiers = 0,
        public bool $hasDefaultValue = false,
        public mixed $defaultValue = null,
        public AttributeCollection $attributes = new AttributeCollection(),
        ?string $description = null,
    ) {
        parent::__construct($type, $description);
    }

    public function isVariadic(): bool
    {
        return ($this->modifiers & ParameterFlag::IS_VARIADIC) !== 0;
    }

    public function isPromoted(): bool
    {
        return ($this->modifiers & ParameterFlag::IS_PROMOTED) !== 0;
    }

    public function isDeprecated(): bool
    {
        return ($this->modifiers & ParameterFlag::IS_DEPRECATED) !== 0;
    }
}
