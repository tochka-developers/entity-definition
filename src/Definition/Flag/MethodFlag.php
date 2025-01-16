<?php

declare(strict_types=1);

namespace Tochka\EntityDefinition\Definition\Flag;

final class MethodFlag
{
    public const IS_PUBLIC = 1;
    public const IS_PROTECTED = 2;
    public const IS_PRIVATE = 4;

    public const IS_STATIC = 16;
    public const IS_FINAL = 32;
    public const IS_ABSTRACT = 64;

    public const IS_DEPRECATED = 1024;
    public const IS_CONSTRUCTOR = 2048;
    public const IS_DESTRUCTOR = 4096;
}
