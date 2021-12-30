<?php declare(strict_types=1);

namespace Inferred\Tools\Templates;

use Inferred\Types\T1;

/**
 *  @codeCoverageIgnore
 */
abstract class ListTemplate implements \Iterator
{
    protected array $list = [];
    protected int $position = 0;
    protected int $null;

    public function add(T1 $item)
    {
        $this->list[] = $item;
    }

    public function get(int $i): T1
    {
        return $this->list[$i];
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function current(): ?T1
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
