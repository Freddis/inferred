<?php declare(strict_types=1);

namespace Inferred\Values;

interface IDefaultValue
{
    public function toString(): string;

    public function getValue(): mixed;
}
