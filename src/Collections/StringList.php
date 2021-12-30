<?php declare(strict_types=1);

namespace Inferred\Collections;

use Countable;
use Iterator;

class StringList implements Iterator, Countable
{
    protected array $list = [];
    protected int $position = 0;

    public function add(string $item)
    {
        $this->list[] = $item;
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function current(): ?string
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

    public function join(string $separator): string
    {
        if ($this->count() == 0) {
            return "";
        }
        return join($separator, $this->list);
    }
}
