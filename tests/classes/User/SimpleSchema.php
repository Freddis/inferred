<?php declare(strict_types=1);

namespace InferredTests\User;

use Inferred\Collections\FieldSchemaList;
use Inferred\Collections\MethodSchemaList;
use Inferred\Collections\StringList;
use Inferred\Schema;

class SimpleSchema extends Schema
{
    public function getMethods(): MethodSchemaList
    {
        return new MethodSchemaList();
    }

    public function getFields(): FieldSchemaList
    {
        return new FieldSchemaList();
    }

    public function getUsedNamespaces(): StringList
    {
        return new StringList();
    }

    public function isStrict()
    {
       return false;
    }
}
