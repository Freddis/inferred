<?php declare(strict_types=1);

namespace Inferred\Collections;

use Inferred\PhpDoc\Tag;
use Countable;
use Iterator;

class TagList implements Iterator, Countable
{
    protected array $list = [];
    protected int $position = 0;

    public function add(Tag $item)
    {
        $this->list[] = $item;
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function current(): ?Tag
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

    public function count(): int
    {
       return count($this->list);
    }
}
