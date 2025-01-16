<?php

declare(strict_types=1);

namespace Tochka\EntityDefinition\TypeReflector;

use Tochka\Types\Alias\FalseType;
use Tochka\Types\Alias\IterableType;
use Tochka\Types\Alias\TrueType;
use Tochka\Types\Atomic\ArrayType;
use Tochka\Types\Atomic\BoolType;
use Tochka\Types\Atomic\CallableType;
use Tochka\Types\Atomic\ClassType;
use Tochka\Types\Atomic\FloatType;
use Tochka\Types\Atomic\IntType;
use Tochka\Types\Atomic\MixedType;
use Tochka\Types\Atomic\NeverType;
use Tochka\Types\Atomic\NullType;
use Tochka\Types\Atomic\ObjectType;
use Tochka\Types\Atomic\ResourceType;
use Tochka\Types\Atomic\StringType;
use Tochka\Types\Atomic\VoidType;
use Tochka\Types\Complex\IntersectType;
use Tochka\Types\Complex\UnionType;
use Tochka\Types\TypeInterface;

/**
 * @api
 */
class TypeReflector implements TypeReflectorInterface
{
    public function getType(?\ReflectionType $reflectionType, ?TypeInterface $docBlockType = null): TypeInterface
    {
        if ($reflectionType === null) {
            return $docBlockType ?? new MixedType();
        }

        if ($reflectionType instanceof \ReflectionNamedType) {
            $type = $this->getFromReflectionNamedType($reflectionType);
        } elseif ($reflectionType instanceof \ReflectionUnionType) {
            $type = $this->getFromReflectionUnionType($reflectionType);
        } elseif ($reflectionType instanceof \ReflectionIntersectionType) {
            $type = $this->getFromReflectionIntersectionType($reflectionType);
        } else {
            $type = new MixedType();
        }

        if ($docBlockType !== null && $type->isContravariantTo($docBlockType)) {
            $type = $docBlockType;
        }

        return $type;
    }

    private function getFromReflectionNamedType(\ReflectionNamedType $reflectionType): TypeInterface
    {
        if ($reflectionType->isBuiltin()) {
            $type = $this->getBuiltinType($reflectionType->getName());
        } else {
            $className = ltrim($reflectionType->getName(), '\\');
            $type = new ClassType($className);
        }

        if ($reflectionType->allowsNull()) {
            $type = $type->setNullable();
        }

        return $type;
    }

    private function getBuiltinType(string $name): TypeInterface
    {
        return match ($name) {
            'array' => new ArrayType(),
            'bool' => new BoolType(),
            'callable' => new CallableType(),
            'float' => new FloatType(),
            'int' => new IntType(),
            'iterable' => new IterableType(),
            'never' => new NeverType(),
            'null' => new NullType(),
            'object' => new ObjectType(),
            'resource' => new ResourceType(),
            'string' => new StringType(),
            'void' => new VoidType(),
            'true' => new TrueType(),
            'false' => new FalseType(),
            default => new MixedType(),
        };
    }

    private function getFromReflectionUnionType(\ReflectionUnionType $type): TypeInterface
    {
        $types = $this->getAggregateTypes($type->getTypes());

        return UnionType::mergeTypes(...$types);
    }

    private function getFromReflectionIntersectionType(\ReflectionIntersectionType $type): TypeInterface
    {
        $types = $this->getAggregateTypes($type->getTypes());

        return new IntersectType(...$this->filterTypesForIntersection($types));
    }

    /**
     * @param list<\ReflectionType> $types
     * @return list<TypeInterface>
     */
    private function getAggregateTypes(array $types): array
    {
        return array_map(
            fn(\ReflectionType $type) => $this->getType($type),
            $types,
        );
    }

    /**
     * @param list<TypeInterface> $types
     * @return list<ClassType>
     */
    private function filterTypesForIntersection(array $types): array
    {
        return array_values(
            array_filter(
                $types,
                fn(TypeInterface $type) => $type instanceof ClassType,
            ),
        );
    }
}
