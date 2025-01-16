<?php

declare(strict_types=1);

namespace Tochka\EntityDefinition\Definition\Collection;

use Tochka\EntityDefinition\Definition\Flag\ParameterFlag;
use Tochka\EntityDefinition\Definition\ParameterDefinition;

/**
 * @api
 * @template-extends \IteratorAggregate<int, ParameterDefinition>
 */
interface ParameterDefinitionCollectionInterface extends \IteratorAggregate, \Countable
{
    /**
     * @param int-mask-of<ParameterFlag::*> $filter
     * @return iterable<ParameterDefinition>
     */
    public function filter(int $filter): iterable;

    public function getByName(string $name): ?ParameterDefinition;
}
