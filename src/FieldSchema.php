<?php declare(strict_types=1);

namespace Inferred;

use Inferred\PhpDoc\DocComment;
use Inferred\Types\ITypeSchema;
use Inferred\Types\Visibility;
use Inferred\Values\IDefaultValue;

class FieldSchema
{
    protected string $name;
    protected Visibility $visibility = Visibility::Private;
    protected ?Types\ITypeSchema $type = null;
    protected ?IDefaultValue $defaultValue = null;
    protected ?PhpDoc\DocComment $comment = null;

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

    public function setType(Types\ITypeSchema $type)
    {
        $this->type = $type;
    }

    public function getType(): ?ITypeSchema
    {
        return $this->type;
    }

    public function setDefaultValue(IDefaultValue $value): void
    {
        $this->defaultValue = $value;
    }

    public function getDefaultValue(): ?IDefaultValue
    {
        return $this->defaultValue;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function addDocComment(PhpDoc\DocComment $comment): void
    {
        $this->comment = $comment;
    }

    public function getDocComment(): ?DocComment
    {
        return $this->comment;
    }
}
