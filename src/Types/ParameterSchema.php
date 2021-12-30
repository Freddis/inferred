<?php declare(strict_types=1);

namespace Inferred\Types;

use Inferred\Values\IDefaultValue;

class ParameterSchema implements IParameterSchema
{
    protected string $name;
    protected ?IDefaultValue $defaultValue = null;
    protected ?ITypeSchema $type = null;

    public function __construct(string $name, ?ITypeSchema $type)
    {
        $this->type = $type;
        $this->name = $name;
    }
    public function getName(): string
    {
        return $this->name;
    }

    public function setDefaultValue(IDefaultValue $value) : void {
        $this->defaultValue = $value;
    }

    public function getDefaultValue(): ?IDefaultValue
    {
        return $this->defaultValue;
    }

    public function hasDefaultValue(): bool
    {
       return $this->defaultValue != null;
    }

    public function getType(): ?ITypeSchema
    {
        return $this->type;
    }
}
