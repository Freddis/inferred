<?php declare(strict_types=1);

namespace Inferred;

use Inferred\Collections\ParameterSchemaList;
use Inferred\Types\ITypeSchema;

class MethodSchema extends AbstractField
{
    protected ParameterSchemaList $parameters;
    protected ?string $body = null;
    protected bool $isAbstract = false;
    protected bool $isFinal = false;

    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->parameters = new ParameterSchemaList();
    }

    public function isAbstract(): bool
    {
        return $this->isAbstract;
    }

    public function setIsAbstract(bool $isAbstract): void
    {
        $this->isAbstract = $isAbstract;
    }

    public function setReturnType(Types\ITypeSchema $returnType)
    {
        parent::setType($returnType);
    }

    public function addParameter(Types\ParameterSchema $parameter)
    {
        $this->parameters->add($parameter);
    }

    public function getParameters(): ParameterSchemaList
    {
        return $this->parameters;
    }

    public function getReturnType(): ?ITypeSchema
    {
        return $this->getType();
    }

    public function setBody(string $body) : void
    {
        $this->body = $body;
    }

    public function getBody() : ?string
    {
        return $this->body;
    }

    public function isFinal(): bool
    {
        return $this->isFinal;
    }

    public function setIsFinal(bool $isFinal)
    {
        $this->isFinal = $isFinal;
    }
}
