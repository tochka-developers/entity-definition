<?php

declare(strict_types=1);

namespace Tochka\EntityDefinition\Definition\Collection;

use Tochka\EntityDefinition\Definition\Flag\PropertyFlag;
use Tochka\EntityDefinition\Definition\PropertyDefinition;

/**
 * @api
 * @template-extends \IteratorAggregate<int, PropertyDefinition>
 */
interface PropertyDefinitionCollectionInterface extends \IteratorAggregate, \Countable
{
    /**
     * @param int-mask-of<PropertyFlag::*> $filter
     * @return iterable<PropertyDefinition>
     */
    public function filter(int $filter): iterable;

    public function getByName(string $name): ?PropertyDefinition;
}
