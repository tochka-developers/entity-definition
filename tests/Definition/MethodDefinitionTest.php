<?php

declare(strict_types=1);

namespace TochkaTest\EntityDefinition\Definition;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Tochka\EntityDefinition\Definition\Collection\ParameterDefinitionCollection;
use Tochka\EntityDefinition\Definition\Flag\MethodFlag;
use Tochka\EntityDefinition\Definition\MethodDefinition;
use Tochka\EntityDefinition\Definition\ValueDefinition;
use Tochka\Types\Atomic\MixedType;
use TochkaTest\EntityDefinition\TestCase;

#[CoversClass(MethodDefinition::class)]
class MethodDefinitionTest extends TestCase
{
    use ModifierGenerator;

    public static function modifiersDataProvider(): iterable
    {
        return self::generateModifiers([
            MethodFlag::IS_PUBLIC,
            MethodFlag::IS_PROTECTED,
            MethodFlag::IS_PRIVATE,
            MethodFlag::IS_STATIC,
            MethodFlag::IS_FINAL,
            MethodFlag::IS_ABSTRACT,
            MethodFlag::IS_DEPRECATED,
            MethodFlag::IS_CONSTRUCTOR,
            MethodFlag::IS_DESTRUCTOR,
        ]);
    }

    #[DataProvider('modifiersDataProvider')]
    public function testModifiers(
        int $modifiers,
        bool $isPublic,
        bool $isProtected,
        bool $isPrivate,
        bool $isStatic,
        bool $isFinal,
        bool $isAbstract,
        bool $isDeprecated,
        bool $isConstructor,
        bool $isDestructor,
    ): void {
        $definition = new MethodDefinition(
            'test',
            'test',
            new ParameterDefinitionCollection([]),
            new ValueDefinition(new MixedType()),
            modifiers: $modifiers,
        );

        $this->assertEquals($isPublic, $definition->isPublic());
        $this->assertEquals($isProtected, $definition->isProtected());
        $this->assertEquals($isPrivate, $definition->isPrivate());
        $this->assertEquals($isStatic, $definition->isStatic());
        $this->assertEquals($isFinal, $definition->isFinal());
        $this->assertEquals($isAbstract, $definition->isAbstract());
        $this->assertEquals($isDeprecated, $definition->isDeprecated());
        $this->assertEquals($isConstructor, $definition->isConstructor());
        $this->assertEquals($isDestructor, $definition->isDestructor());
    }
}
