<?php

declare(strict_types=1);

namespace TochkaTest\EntityDefinition\Definition;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Tochka\EntityDefinition\Definition\Flag\ParameterFlag;
use Tochka\EntityDefinition\Definition\ParameterDefinition;
use Tochka\Types\Atomic\MixedType;
use TochkaTest\EntityDefinition\TestCase;

#[CoversClass(ParameterDefinition::class)]
class ParameterDefinitionTest extends TestCase
{
    use ModifierGenerator;

    public static function modifiersDataProvider(): iterable
    {
        return self::generateModifiers([
            ParameterFlag::IS_VARIADIC,
            ParameterFlag::IS_PROMOTED,
            ParameterFlag::IS_DEPRECATED,
        ]);
    }

    #[DataProvider('modifiersDataProvider')]
    public function testModifiers(
        int $modifiers,
        bool $isVariadic,
        bool $isPromoted,
        bool $isDeprecated,
    ): void {
        $definition = new ParameterDefinition(
            new MixedType(),
            'test',
            modifiers: $modifiers,
        );

        $this->assertEquals($isVariadic, $definition->isVariadic());
        $this->assertEquals($isPromoted, $definition->isPromoted());
        $this->assertEquals($isDeprecated, $definition->isDeprecated());
    }
}
