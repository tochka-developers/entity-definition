<?php

declare(strict_types=1);

namespace Tochka\EntityDefinition\Reflector\ClassDefinition;

use JetBrains\PhpStorm\Deprecated;
use Tochka\DocBlockParser\PhpDoc;
use Tochka\DocBlockParser\PhpDocFactory;
use Tochka\DocBlockParser\Tags\DeprecatedTag;
use Tochka\EntityDefinition\Collection\AttributeCollection;
use Tochka\EntityDefinition\Definition\ClassDefinition;
use Tochka\EntityDefinition\Definition\Collection\MethodDefinitionCollectionInterface;
use Tochka\EntityDefinition\Definition\Collection\PropertyDefinitionCollectionInterface;
use Tochka\EntityDefinition\Definition\Collection\ProxyMethodDefinitionCollection;
use Tochka\EntityDefinition\Definition\Collection\ProxyPropertyDefinitionCollection;
use Tochka\EntityDefinition\Definition\Flag\ClassFlag;
use Tochka\EntityDefinition\Reflector\MethodDefinition\MethodDefinitionReflectorInterface;
use Tochka\EntityDefinition\Reflector\PropertyDefinition\PropertyDefinitionReflectorInterface;
use Tochka\EntityDefinition\Reflector\Support\AttributesTrait;
use Tochka\EntityDefinition\Reflector\Support\TemplatesTrait;

readonly class ClassDefinitionReflector implements ClassDefinitionReflectorInterface
{
    use AttributesTrait;
    use TemplatesTrait;

    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function __construct(
        private PhpDocFactory $phpDocFactory,
        private MethodDefinitionReflectorInterface $methodReflector,
        private PropertyDefinitionReflectorInterface $propertyReflector,
    ) {}

    public function getClassDefinition(\ReflectionClass $reflectionClass): ClassDefinition
    {
        $attributes = $this->getAttributes($reflectionClass);
        $docBlockClass = $this->phpDocFactory->getPhpDocFromReflector($reflectionClass);

        return new ClassDefinition(
            className: $reflectionClass->getName(),
            properties: $this->getProperties($reflectionClass),
            methods: $this->getMethods($reflectionClass),
            modifiers: $this->getModifiers($reflectionClass, $attributes, $docBlockClass),
            attributes: $attributes,
            templates: $this->getTemplates($docBlockClass),
            description: $docBlockClass?->getDescription(),
        );
    }

    public function getProperties(\ReflectionClass $reflectionClass): PropertyDefinitionCollectionInterface
    {
        return new ProxyPropertyDefinitionCollection($reflectionClass->getProperties(), $this->propertyReflector);
    }

    public function getMethods(\ReflectionClass $reflectionClass): MethodDefinitionCollectionInterface
    {
        return new ProxyMethodDefinitionCollection($reflectionClass->getMethods(), $this->methodReflector);
    }

    /**
     * @return int-mask-of<ClassFlag::*>
     */
    private function getModifiers(\ReflectionClass $reflection, AttributeCollection $attributes, ?PhpDoc $docBlock): int
    {
        $modifiers = 0;
        if ($reflection->isAbstract()) {
            $modifiers |= ClassFlag::IS_ABSTRACT;
        }
        if ($reflection->isFinal()) {
            $modifiers |= ClassFlag::IS_FINAL;
        }
        if ($reflection->isReadOnly()) {
            $modifiers |= ClassFlag::IS_READONLY;
        }
        if ($attributes->hasByType(Deprecated::class) || $docBlock?->hasTagsByType(DeprecatedTag::class)) {
            $modifiers |= ClassFlag::IS_DEPRECATED;
        }

        return $modifiers;
    }
}
