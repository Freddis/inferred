<?php declare(strict_types=1);

namespace Inferred\Collections;

use Inferred\Types\TemplateType;
use Iterator;

class TemplateTypeList implements Iterator
{
    protected array $list = [];
    protected int $position = 0;

    public function add(TemplateType $item)
    {
        $this->list[] = $item;
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function current(): ?TemplateType
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
