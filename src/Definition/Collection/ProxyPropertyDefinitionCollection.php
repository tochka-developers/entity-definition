<?php

declare(strict_types=1);

namespace Tochka\EntityDefinition\Definition\Collection;

use Tochka\EntityDefinition\Definition\PropertyDefinition;
use Tochka\EntityDefinition\Reflector\PropertyDefinition\PropertyDefinitionReflectorInterface;

class ProxyPropertyDefinitionCollection implements PropertyDefinitionCollectionInterface
{
    private ?PropertyDefinitionCollection $collection = null;

    /**
     * @param list<\ReflectionProperty> $propertyReflections
     */
    public function __construct(
        private readonly array $propertyReflections,
        private readonly PropertyDefinitionReflectorInterface $reflector,
    ) {}

    private function getCollection(): PropertyDefinitionCollection
    {
        if ($this->collection === null) {
            $definitions = array_map(
                fn(\ReflectionProperty $reflection) => $this->reflector->getPropertyDefinition($reflection),
                $this->propertyReflections,
            );

            $this->collection = new PropertyDefinitionCollection($definitions);
        }

        return $this->collection;
    }

    public function getIterator(): \Traversable
    {
        return $this->getCollection()->getIterator();
    }

    public function count(): int
    {
        return $this->getCollection()->count();
    }

    public function filter(int $filter): iterable
    {
        return $this->getCollection()->filter($filter);
    }

    public function getByName(string $name): ?PropertyDefinition
    {
        return $this->getCollection()->getByName($name);
    }
}
