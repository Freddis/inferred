<?php declare(strict_types=1);

namespace Inferred\Values;

class DefaultValue implements IDefaultValue
{
    protected mixed $value;

    public function __construct(mixed $value)
    {
        $this->value = $value;
    }

    public function toString(): string
    {
      $result = var_export($this->value,true);
      $trimmed = str_replace("\n",'',$result);
      return $trimmed;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }
}
