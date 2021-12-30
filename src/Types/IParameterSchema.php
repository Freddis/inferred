<?php declare(strict_types=1);

namespace Inferred\Types;

use Inferred\Values\IDefaultValue;

interface IParameterSchema
{
    public function getName(): string;

    public function getType() : ?ITypeSchema;

    public function hasDefaultValue(): bool;

    public function getDefaultValue(): ?IDefaultValue;
}
