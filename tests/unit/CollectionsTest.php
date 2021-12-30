<?php declare(strict_types=1);

use Inferred\Collections\FieldSchemaList;
use Inferred\Collections\MethodSchemaList;
use Inferred\Collections\ParameterSchemaList;
use Inferred\Collections\TemplateTypeList;
use Inferred\FieldSchema;
use Inferred\MethodSchema;
use Inferred\Types\ParameterSchema;
use Inferred\Types\T1;
use Inferred\Types\TemplateType;
use PHPUnit\Framework\TestCase;

class CollectionsTest extends TestCase
{
    public function testMethodSchemaList()
    {
        //actually this test is to figure out why code coverage doesn't work properly. But well, let's keep it.
        $lists = [
            new MethodSchemaList(),
            new FieldSchemaList(),
            new ParameterSchemaList(),
            new TemplateTypeList()
        ];
        $items = [
            new MethodSchema("test"),
            new FieldSchema("test"),
            new ParameterSchema("test", null),
            new TemplateType(T1::class,T1::class),
        ];
        foreach($lists as $i => $list) {
            $list->add($items[$i]);
            foreach ($list as $key => $value) {
                $this->assertEquals($items[$i], $value);
            }
        }


    }
}
