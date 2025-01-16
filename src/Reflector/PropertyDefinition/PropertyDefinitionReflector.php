<?php

declare(strict_types=1);

namespace Tochka\EntityDefinition\Reflector\PropertyDefinition;

use JetBrains\PhpStorm\Deprecated;
use Tochka\DocBlockParser\PhpDoc;
use Tochka\DocBlockParser\PhpDocFactory;
use Tochka\DocBlockParser\Tags\DeprecatedTag;
use Tochka\DocBlockParser\Tags\ParamTag;
use Tochka\DocBlockParser\Tags\VarTag;
use Tochka\EntityDefinition\Collection\AttributeCollection;
use Tochka\EntityDefinition\Definition\Flag\PropertyFlag;
use Tochka\EntityDefinition\Definition\PropertyDefinition;
use Tochka\EntityDefinition\Reflector\Support\AttributesTrait;
use Tochka\EntityDefinition\TypeReflector\TypeReflectorInterface;

readonly class PropertyDefinitionReflector implements PropertyDefinitionReflectorInterface
{
    use AttributesTrait;

    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function __construct(
        private PhpDocFactory $phpDocFactory,
        private TypeReflectorInterface $typeReflector,
    ) {}

    /**
     * @throws \ReflectionException
     */
    public function getPropertyDefinition(\ReflectionProperty $reflectionProperty): PropertyDefinition
    {
        $hasDeprecatedTag = false;

        if ($reflectionProperty->isPromoted()) {
            $reflectionMethod = $reflectionProperty->getDeclaringClass()->getConstructor();
            if ($reflectionMethod === null) {
                throw new \RuntimeException(
                    sprintf(
                        'Error while get information about promoted property: not found constructor in class [%s]',
                        $reflectionProperty->getDeclaringClass()->getName(),
                    ),
                );
            }
            $promotedParameter = $this->findPromotedParameter($reflectionMethod, $reflectionProperty->getName());
            if ($promotedParameter !== null) {
                $hasDefaultValue = $promotedParameter->isOptional();
                $defaultValue = $hasDefaultValue ? $promotedParameter->getDefaultValue() : null;
            } else {
                $hasDefaultValue = $reflectionProperty->hasDefaultValue();
                $defaultValue = $hasDefaultValue ? $reflectionProperty->getDefaultValue() : null;
            }

            $propertyDocBlockTag = $this->findPromotedPropertyTag(
                $this->phpDocFactory->getPhpDocFromReflector($reflectionMethod),
                $reflectionProperty->getName(),
            );
            $description = $propertyDocBlockTag?->description;
        } else {
            $docBlockProperty = $this->phpDocFactory->getPhpDocFromReflector($reflectionProperty);
            $propertyDocBlockTag = $docBlockProperty?->firstTagByType(VarTag::class);
            $description = $propertyDocBlockTag?->description ?? $docBlockProperty?->getDescription();
            $hasDeprecatedTag = $docBlockProperty?->hasTagsByType(DeprecatedTag::class) ?? false;

            $hasDefaultValue = $reflectionProperty->hasDefaultValue();
            $defaultValue = $hasDefaultValue ? $reflectionProperty->getDefaultValue() : null;
        }

        $propertyType = $this->typeReflector->getType($reflectionProperty->getType(), $propertyDocBlockTag?->type);
        $attributes = $this->getAttributes($reflectionProperty);

        return new PropertyDefinition(
            type: $propertyType,
            name: $reflectionProperty->getName(),
            modifiers: $this->getModifiers($reflectionProperty, $attributes, $hasDeprecatedTag),
            hasDefaultValue: $hasDefaultValue,
            defaultValue: $defaultValue,
            attributes: $attributes,
            description: $description,
        );
    }

    private function findPromotedPropertyTag(?PhpDoc $docBlockMethod, string $propertyName): ?ParamTag
    {
        if ($docBlockMethod === null) {
            return null;
        }

        $paramTags = $docBlockMethod->getTagsByType(ParamTag::class);

        foreach ($paramTags as $tag) {
            if ($tag->name === $propertyName) {
                return $tag;
            }
        }

        return null;
    }

    private function findPromotedParameter(\ReflectionMethod $reflectionMethod, string $propertyName): ?\ReflectionParameter
    {
        $parameters = $reflectionMethod->getParameters();
        foreach ($parameters as $parameter) {
            if ($parameter->name === $propertyName && $parameter->isPromoted()) {
                return $parameter;
            }
        }

        return null;
    }

    /**
     * @return int-mask-of<PropertyFlag::*>
     */
    private function getModifiers(
        \ReflectionProperty $reflection,
        AttributeCollection $attributes,
        bool $hasDeprecatedTag,
    ): int {
        $modifiers = 0;
        if ($reflection->isStatic()) {
            $modifiers |= PropertyFlag::IS_STATIC;
        }
        if ($reflection->isPublic()) {
            $modifiers |= PropertyFlag::IS_PUBLIC;
        }
        if ($reflection->isProtected()) {
            $modifiers |= PropertyFlag::IS_PROTECTED;
        }
        if ($reflection->isPrivate()) {
            $modifiers |= PropertyFlag::IS_PRIVATE;
        }
        if ($reflection->isPromoted()) {
            $modifiers |= PropertyFlag::IS_PROMOTED;
        }
        if ($reflection->isReadOnly()) {
            $modifiers |= PropertyFlag::IS_READONLY;
        }
        if ($attributes->hasByType(Deprecated::class) || $hasDeprecatedTag) {
            $modifiers |= PropertyFlag::IS_DEPRECATED;
        }

        return $modifiers;
    }
}
