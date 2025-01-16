<?php

declare(strict_types=1);

namespace Tochka\EntityDefinition\Definition\Collection;

use Tochka\EntityDefinition\Definition\PropertyDefinition;

readonly class PropertyDefinitionCollection implements PropertyDefinitionCollectionInterface
{
    /**
     * @param list<PropertyDefinition> $definitions
     */
    public function __construct(
        private array $definitions,
    ) {}

    public function getIterator(): \Traversable
    {
        foreach ($this->definitions as $definition) {
            yield $definition;
        }
    }

    public function count(): int
    {
        return count($this->definitions);
    }

    public function filter(int $filter): iterable
    {
        foreach ($this->definitions as $definition) {
            if (($definition->modifiers & $filter) === $filter) {
                yield $definition;
            }
        }
    }

    public function getByName(string $name): ?PropertyDefinition
    {
        foreach ($this->definitions as $definition) {
            if ($definition->name === $name) {
                return $definition;
            }
        }

        return null;
    }
}
