<?php

declare(strict_types=1);

namespace Tochka\EntityDefinition\Reflector\PropertyDefinition;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class PropertyDefinitionReflectorFactory
{
    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container): PropertyDefinitionReflectorInterface
    {
        $collector = $container->get(PropertyDefinitionReflector::class);

        return new PropertyDefinitionCacheableReflector($collector);
    }
}
