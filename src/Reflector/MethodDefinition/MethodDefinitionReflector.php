<?php

declare(strict_types=1);

namespace Tochka\EntityDefinition\Reflector\MethodDefinition;

use JetBrains\PhpStorm\Deprecated;
use Tochka\DocBlockParser\PhpDoc;
use Tochka\DocBlockParser\PhpDocFactory;
use Tochka\DocBlockParser\Tags\DeprecatedTag;
use Tochka\DocBlockParser\Tags\ParamTag;
use Tochka\DocBlockParser\Tags\ReturnTag;
use Tochka\DocBlockParser\Tags\ThrowsTag;
use Tochka\EntityDefinition\Collection\AttributeCollection;
use Tochka\EntityDefinition\Definition\Collection\ParameterDefinitionCollectionInterface;
use Tochka\EntityDefinition\Definition\Collection\ProxyParameterDefinitionCollection;
use Tochka\EntityDefinition\Definition\Flag\MethodFlag;
use Tochka\EntityDefinition\Definition\MethodDefinition;
use Tochka\EntityDefinition\Definition\ValueDefinition;
use Tochka\EntityDefinition\Reflector\ParameterDefinition\ParameterDefinitionReflectorInterface;
use Tochka\EntityDefinition\Reflector\Support\AttributesTrait;
use Tochka\EntityDefinition\Reflector\Support\TemplatesTrait;
use Tochka\EntityDefinition\TypeReflector\TypeReflectorInterface;
use Tochka\Types\TypeInterface;

readonly class MethodDefinitionReflector implements MethodDefinitionReflectorInterface
{
    use AttributesTrait;
    use TemplatesTrait;

    /**
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function __construct(
        private PhpDocFactory $phpDocFactory,
        private TypeReflectorInterface $typeReflector,
        private ParameterDefinitionReflectorInterface $parameterReflector,
    ) {}

    public function getMethodDefinition(\ReflectionMethod $reflectionMethod): MethodDefinition
    {
        $docBlockClass = $this->phpDocFactory->getPhpDocFromReflector($reflectionMethod->getDeclaringClass());
        $docBlockMethod = $this->phpDocFactory->getPhpDocFromReflector($reflectionMethod);
        $attributes = $this->getAttributes($reflectionMethod);

        return new MethodDefinition(
            className: $reflectionMethod->getDeclaringClass()->getName(),
            methodName: $reflectionMethod->getName(),
            parameters: $this->getParameters($reflectionMethod, $docBlockMethod),
            returnValue: $this->getReturnValueFromReflector($reflectionMethod->getReturnType(), $docBlockMethod),
            modifiers: $this->getModifiers($reflectionMethod, $attributes, $docBlockMethod),
            attributes: $attributes,
            templates: $this->getTemplates($docBlockClass)->merge($this->getTemplates($docBlockMethod)),
            throws: $this->getThrowable($docBlockMethod),
            description: $docBlockMethod?->getDescription(),
        );
    }

    private function getReturnValueFromReflector(
        ?\ReflectionType $reflectionType,
        ?PhpDoc $methodDocBlock,
    ): ValueDefinition {
        $docBlockReturnType = null;
        $description = null;

        if ($methodDocBlock !== null) {
            $returnTag = $methodDocBlock->firstTagByType(ReturnTag::class);
            if ($returnTag !== null) {
                $docBlockReturnType = $returnTag->type;
                $description = $returnTag->description;
            }
        }

        return new ValueDefinition(
            type: $this->typeReflector->getType($reflectionType, $docBlockReturnType),
            description: $description,
        );
    }

    /**
     * @param PhpDoc|null $methodDocBlock
     * @return list<TypeInterface>
     */
    private function getThrowable(?PhpDoc $methodDocBlock): array
    {
        return array_map(
            fn(ThrowsTag $tag) => $tag->type,
            $methodDocBlock?->getTagsByType(ThrowsTag::class) ?? [],
        );
    }

    public function getParameters(
        \ReflectionMethod $reflectionMethod,
        ?PhpDoc $docBlockMethod = null,
    ): ParameterDefinitionCollectionInterface {
        $parameterReflections = array_map(
            fn(\ReflectionParameter $reflectionParameter) => [
                $reflectionParameter,
                $this->findParameterTag($docBlockMethod, $reflectionParameter->getName()),
            ],
            $reflectionMethod->getParameters(),
        );

        return new ProxyParameterDefinitionCollection($parameterReflections, $this->parameterReflector);
    }

    private function findParameterTag(?PhpDoc $docBlockMethod, string $parameterName): ?ParamTag
    {
        if ($docBlockMethod === null) {
            return null;
        }

        $paramTags = $docBlockMethod->getTagsByType(ParamTag::class);

        foreach ($paramTags as $tag) {
            if ($tag->name === $parameterName) {
                return $tag;
            }
        }

        return null;
    }

    /**
     * @return int-mask-of<MethodFlag::*>
     */
    private function getModifiers(
        \ReflectionMethod $reflection,
        AttributeCollection $attributes,
        ?PhpDoc $docBlock,
    ): int {
        $modifiers = 0;
        if ($reflection->isPublic()) {
            $modifiers |= MethodFlag::IS_PUBLIC;
        }
        if ($reflection->isProtected()) {
            $modifiers |= MethodFlag::IS_PROTECTED;
        }
        if ($reflection->isPrivate()) {
            $modifiers |= MethodFlag::IS_PRIVATE;
        }
        if ($reflection->isStatic()) {
            $modifiers |= MethodFlag::IS_STATIC;
        }
        if ($reflection->isFinal()) {
            $modifiers |= MethodFlag::IS_FINAL;
        }
        if ($reflection->isAbstract()) {
            $modifiers |= MethodFlag::IS_ABSTRACT;
        }
        if ($reflection->isConstructor()) {
            $modifiers |= MethodFlag::IS_CONSTRUCTOR;
        }
        if ($reflection->isDestructor()) {
            $modifiers |= MethodFlag::IS_DESTRUCTOR;
        }
        if ($attributes->hasByType(Deprecated::class) || $docBlock?->hasTagsByType(DeprecatedTag::class)) {
            $modifiers |= MethodFlag::IS_DEPRECATED;
        }

        return $modifiers;
    }
}
