<?php declare(strict_types=1);

namespace Inferred;

use Inferred\Collections\ParameterSchemaList;
use Inferred\Types\ITypeSchema;
use Inferred\Types\Visibility;

class MethodSchema
{
    protected string $name;
    protected ParameterSchemaList $parameters;
    protected ?Types\ITypeSchema $returnType = null;
    protected Visibility $visibility = Visibility::Private;
    private ?string $body = null;
    private ?PhpDoc\DocComment $comment = null;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->parameters = new ParameterSchemaList();
    }

    public function setReturnType(Types\ITypeSchema $returnType)
    {
        $this->returnType = $returnType;
    }

    public function addParameter(Types\ParameterSchema $parameter)
    {
        $this->parameters->add($parameter);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getParameters(): ParameterSchemaList
    {
        return $this->parameters;
    }

    public function getVisibility(): Visibility
    {
        return $this->visibility;
    }

    public function setVisibility(Visibility $visibility): void
    {
        $this->visibility = $visibility;
    }

    public function getReturnType(): ?ITypeSchema
    {
        return $this->returnType;
    }

    public function setBody(string $body) : void
    {
        $this->body = $body;
    }

    public function isAbstract() : bool {
        return $this->body === null;
    }

    public function getBody() : ?string
    {
        return $this->body;
    }

    public function addDocComment(PhpDoc\DocComment $comment)
    {
        $this->comment = $comment;
    }

    public function getDocComment() : ?PhpDoc\DocComment {
        return $this->comment;
    }
}
