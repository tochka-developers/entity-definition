<?php

declare(strict_types=1);

namespace Tochka\EntityDefinition\Definition\Flag;

final class PropertyFlag
{
    public const IS_STATIC = 16;

    public const IS_PUBLIC = 1;
    public const IS_PROTECTED = 2;
    public const IS_PRIVATE = 4;

    public const IS_READONLY = 65536;

    public const IS_DEPRECATED = 1024;
    public const IS_PROMOTED = 2048;
}
