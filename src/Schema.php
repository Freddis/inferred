<?php declare(strict_types=1);

namespace Inferred;


use Inferred\Collections\FieldSchemaList;
use Inferred\Collections\MethodSchemaList;
use Inferred\Collections\StringList;

abstract class Schema
{
    protected string $name;
    protected ?string $parent = null;
    protected ?string $namespace = null;

    abstract public function getMethods(): MethodSchemaList;

    abstract public function getFields(): FieldSchemaList;

    abstract public function getUsedNamespaces(): StringList;

    abstract public function isStrict();

    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

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

    public function setNamespace(string $namespace)
    {
        $this->namespace = $namespace;
    }
}
