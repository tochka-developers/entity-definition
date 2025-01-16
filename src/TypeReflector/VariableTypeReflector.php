<?php

declare(strict_types=1);

namespace Tochka\EntityDefinition\TypeReflector;

use Tochka\Types\Alias\FloatConstType;
use Tochka\Types\Alias\IntConstType;
use Tochka\Types\Alias\ListType;
use Tochka\Types\Alias\StringConstType;
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
use Tochka\Types\Atomic\StringType;
use Tochka\Types\Misc\KeyShapeItem;
use Tochka\Types\Misc\KeyShapeItems;
use Tochka\Types\Misc\ShapeItem;
use Tochka\Types\Misc\ShapeItems;
use Tochka\Types\TypeInterface;

/**
 * @api
 */
class VariableTypeReflector implements VariableTypeReflectorInterface
{
    /**
     * @template TValueType
     * @param TValueType $variable
     * @return TypeInterface<TValueType>
     * @psalm-suppress NoValue
     */
    public function getVariableType(mixed $variable, bool $atomic = false): TypeInterface
    {
        /** @var TypeInterface<TValueType> */
        return match (true) {
            is_null($variable) => new NullType(),
            is_int($variable) => $atomic ? new IntType() : new IntConstType($variable),
            is_float($variable) => $atomic ? new FloatType() : new FloatConstType($variable),
            is_string($variable) => $atomic ? new StringType() : new StringConstType($variable),
            is_bool($variable) => $atomic ? new BoolType() : new BoolType($variable),
            is_array($variable) => $atomic ? new ArrayType() : $this->getArrayType($variable),
            is_object($variable) => $this->getObjectType($variable),
            default => new MixedType(),
        };
    }

    /**
     * @template TArrayType of array
     * @psalm-param TArrayType $variable
     * @return ArrayType<TArrayType>
     */
    private function getArrayType(array $variable): ArrayType
    {
        if ($variable === []) {
            /** @var ArrayType<TArrayType> */
            return new ListType(new NeverType());
        }

        if (array_is_list($variable)) {
            /** @psalm-suppress InvalidArgument */
            $shapeItems = new ShapeItems(
                ...array_map(
                    fn(mixed $item) => new ShapeItem($this->getVariableType($item, true)),
                    $variable,
                ),
            );

            /** @var ArrayType<TArrayType> */
            return new ListType(new NeverType(), $shapeItems);
        }

        $shapes = [];
        foreach ($variable as $key => $value) {
            $shapes[] = new KeyShapeItem($key, $this->getVariableType($value, true));
        }
        /** @psalm-suppress InvalidArgument */
        $shapeItems = new KeyShapeItems(...$shapes);

        /** @var ArrayType<TArrayType> */
        return new ArrayType(new NeverType(), new NeverType(), $shapeItems);
    }

    /**
     * @template TObjectType of object
     * @psalm-param TObjectType $variable
     * @return (TObjectType is \stdClass ? ObjectType : (TObjectType is \Closure ? CallableType : ClassType<TObjectType>))
     */
    private function getObjectType(object $variable): ObjectType|CallableType|ClassType
    {
        if ($variable::class === \stdClass::class) {
            return new ObjectType();
        }
        if ($variable::class === \Closure::class) {
            return new CallableType();
        }

        return new ClassType($variable::class);
    }
}
