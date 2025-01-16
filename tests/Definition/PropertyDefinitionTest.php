<?php

declare(strict_types=1);

namespace TochkaTest\EntityDefinition\Definition;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Tochka\EntityDefinition\Definition\Flag\PropertyFlag;
use Tochka\EntityDefinition\Definition\PropertyDefinition;
use Tochka\Types\Atomic\MixedType;
use TochkaTest\EntityDefinition\TestCase;

#[CoversClass(PropertyDefinition::class)]
class PropertyDefinitionTest extends TestCase
{
    use ModifierGenerator;

    public static function modifiersDataProvider(): iterable
    {
        return self::generateModifiers([
            PropertyFlag::IS_STATIC,
            PropertyFlag::IS_PUBLIC,
            PropertyFlag::IS_PROTECTED,
            PropertyFlag::IS_PRIVATE,
            PropertyFlag::IS_READONLY,
            PropertyFlag::IS_DEPRECATED,
            PropertyFlag::IS_PROMOTED,
        ]);
    }

    #[DataProvider('modifiersDataProvider')]
    public function testModifiers(
        int $modifiers,
        bool $isStatic,
        bool $isPublic,
        bool $isProtected,
        bool $isPrivate,
        bool $isReadonly,
        bool $isDeprecated,
        bool $isPromoted,
    ): void {
        $definition = new PropertyDefinition(
            new MixedType(),
            'test',
            modifiers: $modifiers,
        );

        $this->assertEquals($isStatic, $definition->isStatic());
        $this->assertEquals($isPublic, $definition->isPublic());
        $this->assertEquals($isProtected, $definition->isProtected());
        $this->assertEquals($isPrivate, $definition->isPrivate());
        $this->assertEquals($isReadonly, $definition->isReadonly());
        $this->assertEquals($isDeprecated, $definition->isDeprecated());
        $this->assertEquals($isPromoted, $definition->isPromoted());
    }
}
