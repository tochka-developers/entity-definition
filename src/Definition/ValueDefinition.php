<?php

declare(strict_types=1);

namespace Tochka\EntityDefinition\Definition;

use Tochka\Types\TypeInterface;

/**
 * @api
 */
readonly class ValueDefinition
{
    public function __construct(
        public TypeInterface $type,
        public ?string $description = null,
    ) {}
}
