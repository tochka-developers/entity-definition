<?php

declare(strict_types=1);

namespace Tochka\EntityDefinition\Definition\Flag;

final class ClassFlag
{
    public const IS_ABSTRACT = 16;
    public const IS_FINAL = 32;
    public const IS_READONLY = 65536;

    public const IS_DEPRECATED = 1024;
}
