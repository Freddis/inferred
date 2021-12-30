<?php declare(strict_types=1);

namespace Inferred\Tools\Templates;

use Inferred\Types\T1;

/**
 * Implements downcast for php. Allows IDE to infer methods and fields inferred from the variable.
 * Since PHP doesn't have type casts this class doesn't do any actual work.
 */
final class TypeCasterTemplate
{
    /**
     * @var T1|mixed Value that's going to be cast
     */
    protected mixed $value;

    /**
     * @param T1|mixed $value Value that needs to be cast
     */
    public function __construct(mixed $value = null)
    {
        $this->value = $value;
    }

    /**
     * Checks if the object is of desired type
     *
     * @return bool
     */
    public function hasValue(): bool
    {
        return $this->value instanceof T1;
    }

    /**
     * Returns the object of the correct type
     *
     * @return T1|null
     */
    public function getValue(): ?T1
    {
        if (!$this->hasValue()) {
            return null;
        }
        return $this->value;
    }
}
