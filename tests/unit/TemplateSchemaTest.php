<?php declare(strict_types=1);

use Inferred\Tools\Templates\ListTemplate;
use Inferred\Types\T1;
use Inferred\Types\TemplateType;
use InferredTests\Templates\TypeCasterTemplate;
use InferredTests\User\SomeClass;
use PHPUnit\Framework\TestCase;

class TemplateSchemaTest extends TestCase
{
    public function testMethods()
    {
        if (file_exists(__DIR__ . "/../classes/Generated/TemplateTestClass.php")) {
            unlink(__DIR__ . "/../classes/Generated/TemplateTestClass.php");
        }
        $this->assertFileDoesNotExist(__DIR__ . "/../classes/Generated/TemplateTestClass.php");
        $generator = new Inferred\Generator(__DIR__ . "/../classes/Generated");

        $schema = new Inferred\TemplateSchema(TypeCasterTemplate::class, 'TemplateTestClass');
        $schema->addTemplateType(new TemplateType(T1::class, SomeClass::class));
        $generator->generate($schema);

        $this->assertTrue(file_exists(__DIR__ . "/../classes/Generated/TemplateTestClass.php"), "Generated file isn't created.");
        require_once __DIR__ . "/../classes/Generated/TemplateTestClass.php";
        $generatedClass = 'TemplateTestClass';
        $internal = new SomeClass();
        $object = new $generatedClass($internal);
        $this->assertEquals($internal,$object->getValue());
        $this->assertTrue($object->hasValue());
    }

    public function testFields()
    {
        if (file_exists(__DIR__ . "/../classes/Generated/ListTestClass.php")) {
            unlink(__DIR__ . "/../classes/Generated/ListTestClass.php");
        }
        $this->assertFileDoesNotExist(__DIR__ . "/../classes/Generated/ListTestClass.php");
        $generator = new Inferred\Generator(__DIR__ . "/../classes/Generated");

        $schema = new Inferred\TemplateSchema(ListTemplate::class, 'ListTestClass');
        $schema->addTemplateType(new TemplateType(T1::class, SomeClass::class));
        $generator->generate($schema);

        $this->assertTrue(file_exists(__DIR__ . "/../classes/Generated/ListTestClass.php"), "Generated file isn't created.");
        require_once __DIR__ . "/../classes/Generated/ListTestClass.php";
        $generatedClass = 'ListTestClass';

        $list = new $generatedClass();
        $listItem = new SomeClass();
        $list->add($listItem);
        foreach($list as $obj) {
            $this->assertEquals($listItem,$obj);
        }
        $this->assertEquals($listItem,$list->get(0));
    }
}
