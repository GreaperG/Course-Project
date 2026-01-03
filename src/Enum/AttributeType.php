<?php

declare(strict_types=1);

namespace App\Enum;

enum AttributeType: string
{
    case STRING = 'string';

    case TEXT = 'text';

    case INTEGER = 'int';

    case BOOLEAN = 'bool';
}
