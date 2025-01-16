<?php

declare(strict_types=1);

namespace Tochka\EntityDefinition\Definition;

use Tochka\Types\TypeInterface;

/**
 * @api
 */
readonly class TemplateDefinition
{
    public function __construct(
        public string $name,
        public ?TypeInterface $bound = null,
        public ?TypeInterface $default = null,
        public bool $isCovariant = false,
        public bool $isContravariant = false,
        public ?string $description = null,
    ) {}
}
