<?php declare(strict_types=1);

namespace Inferred;
class Schema
{
    private ?string $parent = null;
    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function addParent(string $class)
    {
        $this->parent = $class;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getParent(): ?string
    {
        return $this->parent;
    }
}
