<?php

declare(strict_types=1);

namespace Tochka\EntityDefinition\Definition;

use Tochka\EntityDefinition\Collection\AttributeCollection;
use Tochka\EntityDefinition\Collection\TemplateCollection;
use Tochka\EntityDefinition\Definition\Collection\MethodDefinitionCollectionInterface;
use Tochka\EntityDefinition\Definition\Collection\PropertyDefinitionCollectionInterface;
use Tochka\EntityDefinition\Definition\Flag\ClassFlag;

/**
 * @api
 * @template TClass
 */
readonly class ClassDefinition
{
    /**
     * @param class-string<TClass> $className
     * @param int-mask-of<ClassFlag::*> $modifiers
     */
    public function __construct(
        public string $className,
        public PropertyDefinitionCollectionInterface $properties,
        public MethodDefinitionCollectionInterface $methods,
        public int $modifiers = 0,
        public AttributeCollection $attributes = new AttributeCollection(),
        public TemplateCollection $templates = new TemplateCollection(),
        public ?string $description = null,
    ) {}

    public function isAbstract(): bool
    {
        return ($this->modifiers & ClassFlag::IS_ABSTRACT) !== 0;
    }

    public function isFinal(): bool
    {
        return ($this->modifiers & ClassFlag::IS_FINAL) !== 0;
    }

    public function isReadonly(): bool
    {
        return ($this->modifiers & ClassFlag::IS_READONLY) !== 0;
    }

    public function isDeprecated(): bool
    {
        return ($this->modifiers & ClassFlag::IS_DEPRECATED) !== 0;
    }
}
