<?php

declare(strict_types=1);

namespace TochkaTest\EntityDefinition\Definition;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Tochka\EntityDefinition\Definition\ClassDefinition;
use Tochka\EntityDefinition\Definition\Collection\MethodDefinitionCollection;
use Tochka\EntityDefinition\Definition\Collection\PropertyDefinitionCollection;
use Tochka\EntityDefinition\Definition\Flag\ClassFlag;
use TochkaTest\EntityDefinition\TestCase;

#[CoversClass(ClassDefinition::class)]
class ClassDefinitionTest extends TestCase
{
    use ModifierGenerator;

    public static function modifiersDataProvider(): iterable
    {
        return self::generateModifiers([
            ClassFlag::IS_ABSTRACT,
            ClassFlag::IS_FINAL,
            ClassFlag::IS_READONLY,
            ClassFlag::IS_DEPRECATED,
        ]);
    }

    #[DataProvider('modifiersDataProvider')]
    public function testModifiers(
        int $modifiers,
        bool $isAbstract,
        bool $isFinal,
        bool $isReadonly,
        bool $isDeprecated,
    ): void {
        $definition = new ClassDefinition(
            'test',
            new PropertyDefinitionCollection([]),
            new MethodDefinitionCollection([]),
            modifiers: $modifiers,
        );

        $this->assertEquals($isAbstract, $definition->isAbstract());
        $this->assertEquals($isFinal, $definition->isFinal());
        $this->assertEquals($isReadonly, $definition->isReadonly());
        $this->assertEquals($isDeprecated, $definition->isDeprecated());
    }
}
