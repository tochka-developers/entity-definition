<?php

declare(strict_types=1);

namespace Tochka\EntityDefinition\Definition\Collection;

use Tochka\EntityDefinition\Definition\MethodDefinition;
use Tochka\EntityDefinition\Reflector\MethodDefinition\MethodDefinitionReflectorInterface;

class ProxyMethodDefinitionCollection implements MethodDefinitionCollectionInterface
{
    private ?MethodDefinitionCollection $collection = null;

    /**
     * @param list<\ReflectionMethod> $methodReflections
     */
    public function __construct(
        private readonly array $methodReflections,
        private readonly MethodDefinitionReflectorInterface $reflector,
    ) {}

    private function getCollection(): MethodDefinitionCollection
    {
        if ($this->collection === null) {
            $definitions = array_map(
                fn(\ReflectionMethod $reflection) => $this->reflector->getMethodDefinition($reflection),
                $this->methodReflections,
            );

            $this->collection = new MethodDefinitionCollection($definitions);
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

    public function getByName(string $name): ?MethodDefinition
    {
        return $this->getCollection()->getByName($name);
    }

    public function getConstructor(): ?MethodDefinition
    {
        return $this->getCollection()->getConstructor();
    }

    public function getDestructor(): ?MethodDefinition
    {
        return $this->getCollection()->getDestructor();
    }
}
