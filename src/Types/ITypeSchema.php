<?php declare(strict_types=1);

namespace Inferred\Types;

interface ITypeSchema
{
    public function isNullable(): bool;

    public function getName(): string;

    public function toString() : string;

}
