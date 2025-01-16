<?php

declare(strict_types=1);

namespace Tochka\EntityDefinition\Definition\Collection;

use Tochka\EntityDefinition\Definition\Flag\MethodFlag;
use Tochka\EntityDefinition\Definition\MethodDefinition;

/**
 * @api
 * @template-extends \IteratorAggregate<int, MethodDefinition>
 */
interface MethodDefinitionCollectionInterface extends \IteratorAggregate, \Countable
{
    /**
     * @param int-mask-of<MethodFlag::*> $filter
     * @return iterable<MethodDefinition>
     */
    public function filter(int $filter): iterable;

    public function getByName(string $name): ?MethodDefinition;

    public function getConstructor(): ?MethodDefinition;

    public function getDestructor(): ?MethodDefinition;
}
