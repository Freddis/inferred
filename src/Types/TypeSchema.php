<?php declare(strict_types=1);

namespace Inferred\Types;

class TypeSchema implements ITypeSchema
{
    protected string $type;
    protected bool $isNullable = false;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    public function setNullable(bool $isNullable)
    {
        $this->isNullable = $isNullable;
    }

    public function isNullable(): bool
    {
        return $this->isNullable;
    }

    public function getName(): string
    {
        return $this->type;
    }

    public function toString(): string
    {
        $string = ($this->isNullable ? '?' : '') . '\\' . $this->getName();
        return $string;
    }
}
