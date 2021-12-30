<?php declare(strict_types=1);

namespace Inferred\Types;

enum Visibility
{
    case Protected;
    case Public;
    case Private;

    function toString(): string
    {
        return match ($this) {
            self::Public => 'public',
            self::Protected => 'protected',
            default => 'private',
        };
    }

}
