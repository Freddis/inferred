<?php declare(strict_types=1);

use Inferred\Tools\Templates\SingletonTemplate;
use Inferred\Tools\Templates\TypeCasterTemplate;
use Inferred\Types\T1;
use Inferred\Types\TemplateType;
use InferredTests\User\SimpleSchema;
use InferredTests\User\SomeClass;
use PHPUnit\Framework\TestCase;

class GeneratorTest extends TestCase
{
    public function testGeneratorCreatesFile()
    {
        if (file_exists(__DIR__ . '/../classes/Generated/TestClass.php')) {
            unlink(__DIR__ . '/../classes/Generated/TestClass.php');
        }
        $this->assertFileDoesNotExist(__DIR__ . "/../classes/Generated/TestClass.php");
        $generator = new Inferred\Generator(__DIR__ . "/../classes/Generated");
        $schema = new SimpleSchema("TestClass");
        $generator->generate($schema);

        $this->assertTrue(file_exists(__DIR__ . "/../classes/Generated/TestClass.php"), "Generated file isn't created.");
    }

    public function testSchemaNamespace()
    {
        if (file_exists(__DIR__ . "/../classes/Generated/SubNamespace/SubNamespaceClass.php")) {
            unlink(__DIR__ . "/../classes/Generated/SubNamespace/SubNamespaceClass.php");
        }
        $this->assertFileDoesNotExist(__DIR__ . "/../classes/Generated/SubNamespace/SubNamespaceClass.php");
        $generator = new Inferred\Generator(__DIR__ . "/../classes/Generated", 'InferredTests\Generated');
        $schema = new SimpleSchema("SubNamespaceClass");
        $schema->setNamespace('InferredTests\Generated\SubNamespace');
        $generator->generate($schema);

        $this->assertTrue(file_exists(__DIR__ . "/../classes/Generated/SubNamespace"), "Subnamespace folder doesn't exist");
        $this->assertTrue(file_exists(__DIR__ . "/../classes/Generated/SubNamespace/SubNamespaceClass.php"), "Generated file isn't created.");
        $obj = new InferredTests\Generated\GeneratorNamespacedClass();
    }

    public function testGeneratorNamespace()
    {
        if (file_exists(__DIR__ . '/../classes/Generated/GeneratorNamespacedClass.php')) {
            unlink(__DIR__ . '/../classes/Generated/GeneratorNamespacedClass.php');
        }
        $this->assertFileDoesNotExist(__DIR__ . "/../classes/Generated/GeneratorNamespacedClass.php");
        $generator = new Inferred\Generator(__DIR__ . "/../classes/Generated", 'InferredTests\Generated');
        $schema = new SimpleSchema("GeneratorNamespacedClass");
        $generator->generate($schema);

        $this->assertTrue(file_exists(__DIR__ . "/../classes/Generated/GeneratorNamespacedClass.php"), "Generated file isn't created.");
        $obj = new InferredTests\Generated\GeneratorNamespacedClass();
    }

    public function testStaticFields()
    {
        if (file_exists(__DIR__ . "/../classes/Generated/StaticTestClass.php")) {
            unlink(__DIR__ . "/../classes/Generated/StaticTestClass.php");
        }
        $this->assertFileDoesNotExist(__DIR__ . "/../classes/Generated/StaticTestClass.php");
        $generator = new Inferred\Generator(__DIR__ . "/../classes/Generated");

        $schema = new Inferred\TemplateSchema(SingletonTemplate::class, 'StaticTestClass');
        $schema->addTemplateType(new TemplateType(T1::class, SomeClass::class));
        $generator->generate($schema);

        $this->assertTrue(file_exists(__DIR__ . "/../classes/Generated/StaticTestClass.php"), "Generated file isn't created.");
        require_once __DIR__ . "/../classes/Generated/StaticTestClass.php";

        $singleton = StaticTestClass::getInstance();
        $this->assertNotNull($singleton);
        $this->assertEquals(get_class($singleton), get_class(new SomeClass()));
    }

    public function testDocComments()
    {
        if (file_exists(__DIR__ . "/../classes/Generated/CommentedTestClass.php")) {
            unlink(__DIR__ . "/../classes/Generated/CommentedTestClass.php");
        }
        $this->assertFileDoesNotExist(__DIR__ . "/../classes/Generated/CommentedTestClass.php");
        $generator = new Inferred\Generator(__DIR__ . "/../classes/Generated");

        $schema = new Inferred\TemplateSchema(TypeCasterTemplate::class, 'CommentedTestClass');
        $schema->addTemplateType(new TemplateType(T1::class, SomeClass::class));
        $generator->generate($schema);

        require_once __DIR__ . "/../classes/Generated/CommentedTestClass.php";

        $reflected = new ReflectionClass("CommentedTestClass");
        $this->assertNotFalse($reflected->getDocComment(), "Class comment is missing");
        $this->assertStringContainsString("Implements downcast for php. Allows IDE to infer methods and fields inferred from the variable.", $reflected->getDocComment(), "Class comment is wrong");

        $methodComment = $reflected->getMethod("getValue")->getDocComment();
        $this->assertNotFalse($methodComment, "Method comment is missing");
        $this->assertStringContainsString("SomeClass", $methodComment, "Method comment is wrong");

        $fieldComment = $reflected->getProperty("value")->getDocComment();
        $this->assertNotFalse($fieldComment, "Field comment is missing");
        $this->assertStringContainsString("SomeClass", $fieldComment, "Field comment is wrong");
    }
}
