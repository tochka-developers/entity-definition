<?php

declare(strict_types=1);

namespace Tochka\EntityDefinition\Definition;

use Tochka\EntityDefinition\Collection\AttributeCollection;
use Tochka\EntityDefinition\Collection\TemplateCollection;
use Tochka\EntityDefinition\Definition\Collection\ParameterDefinitionCollectionInterface;
use Tochka\EntityDefinition\Definition\Flag\MethodFlag;
use Tochka\Types\TypeInterface;

/**
 * @api
 * @template TClass
 */
readonly class MethodDefinition
{
    /**
     * @param class-string<TClass> $className
     * @param list<TypeInterface> $throws
     * @param int-mask-of<MethodFlag::*> $modifiers
     */
    public function __construct(
        public string $className,
        public string $methodName,
        public ParameterDefinitionCollectionInterface $parameters,
        public ValueDefinition $returnValue,
        public int $modifiers = 0,
        public AttributeCollection $attributes = new AttributeCollection(),
        public TemplateCollection $templates = new TemplateCollection(),
        public array $throws = [],
        public ?string $description = null,
    ) {}

    public function isPublic(): bool
    {
        return ($this->modifiers & MethodFlag::IS_PUBLIC) !== 0;
    }

    public function isProtected(): bool
    {
        return ($this->modifiers & MethodFlag::IS_PROTECTED) !== 0;
    }

    public function isPrivate(): bool
    {
        return ($this->modifiers & MethodFlag::IS_PRIVATE) !== 0;
    }

    public function isStatic(): bool
    {
        return ($this->modifiers & MethodFlag::IS_STATIC) !== 0;
    }

    public function isFinal(): bool
    {
        return ($this->modifiers & MethodFlag::IS_FINAL) !== 0;
    }

    public function isAbstract(): bool
    {
        return ($this->modifiers & MethodFlag::IS_ABSTRACT) !== 0;
    }

    public function isDeprecated(): bool
    {
        return ($this->modifiers & MethodFlag::IS_DEPRECATED) !== 0;
    }

    public function isConstructor(): bool
    {
        return ($this->modifiers & MethodFlag::IS_CONSTRUCTOR) !== 0;
    }

    public function isDestructor(): bool
    {
        return ($this->modifiers & MethodFlag::IS_DESTRUCTOR) !== 0;
    }
}
