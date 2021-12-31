<?php declare(strict_types=1);

namespace Inferred;

use Inferred\Values\IDefaultValue;

class FieldSchema extends AbstractField
{
    protected ?IDefaultValue $defaultValue = null;

    public function setDefaultValue(IDefaultValue $value): void
    {
        $this->defaultValue = $value;
    }

    public function getDefaultValue(): ?IDefaultValue
    {
        return $this->defaultValue;
    }
}
