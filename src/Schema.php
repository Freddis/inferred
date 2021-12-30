<?php declare(strict_types=1);

namespace Inferred;


use Inferred\Collections\FieldSchemaList;
use Inferred\Collections\MethodSchemaList;

abstract class Schema
{
    protected string $name;
    protected ?string $parent = null;

    abstract public function getMethods(): MethodSchemaList;

    abstract public function getFields() : FieldSchemaList;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function addParent(string $class)
    {
        $this->parent = $class;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getParent(): ?string
    {
        return $this->parent;
    }

    public function hasParent(): bool
    {
        return $this->parent != null;
    }
}
