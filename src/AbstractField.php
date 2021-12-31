<?php declare(strict_types=1);

namespace Inferred;

use Inferred\PhpDoc\DocComment;
use Inferred\Types\ITypeSchema;
use Inferred\Types\Visibility;

abstract class AbstractField implements IField
{
    protected string $name;
    protected Visibility $visibility = Visibility::Private;
    protected ?Types\ITypeSchema $type = null;
    protected ?PhpDoc\DocComment $comment = null;
    protected bool $isStatic = false;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getVisibility(): Visibility
    {
        return $this->visibility;
    }

    public function setVisibility(Visibility $visibility): void
    {
        $this->visibility = $visibility;
    }

    public function setType(Types\ITypeSchema $type): void
    {
        $this->type = $type;
    }

    public function getType(): ?ITypeSchema
    {
        return $this->type;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setDocComment(PhpDoc\DocComment $comment): void
    {
        $this->comment = $comment;
    }

    public function getDocComment(): ?DocComment
    {
        return $this->comment;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function isStatic(): bool
    {
        return $this->isStatic;
    }

    public function setIsStatic(bool $isStatic): void
    {
        $this->isStatic = $isStatic;
    }
}
