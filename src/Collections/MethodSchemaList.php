<?php declare(strict_types=1);

namespace Inferred\Collections;

use Inferred\MethodSchema;
use Iterator;

class MethodSchemaList implements Iterator
{
    protected array $list = [];
    protected int $position = 0;

    public function add(MethodSchema $item)
    {
        $this->list[] = $item;
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function current(): ?MethodSchema
    {
        return $this->list[$this->position];
    }

    public function key(): int
    {
        return $this->position;
    }

    public function next(): void
    {
        ++$this->position;
    }

    public function valid(): bool
    {
        return isset($this->list[$this->position]);
    }
}
