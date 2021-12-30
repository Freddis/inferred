<?php declare(strict_types=1);

namespace Inferred\PhpDoc;

class SimpleTag extends Tag
{

    protected string $description;

    public function __construct(string $name, $description)
    {
        parent::__construct($name);
        $this->description = $description;
    }

    public function toString() : string
    {
       return "@{$this->name} {$this->description}";
    }

    public function getDescription()
    {
        return $this->description;
    }
}
