<?php declare(strict_types=1);

namespace Inferred\PhpDoc;

abstract class Tag
{
    protected string $name;

    abstract public function toString(): string;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
