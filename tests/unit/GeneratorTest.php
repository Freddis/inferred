<?php declare(strict_types=1);

use InferredTests\User\SimpleSchema;
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
        if (file_exists(__DIR__ ."/../classes/Generated/SubNamespace/SubNamespaceClass.php")) {
            unlink(__DIR__ ."/../classes/Generated/SubNamespace/SubNamespaceClass.php");
        }
        $this->assertFileDoesNotExist(__DIR__ . "/../classes/Generated/SubNamespace/SubNamespaceClass.php");
        $generator = new Inferred\Generator(__DIR__ . "/../classes/Generated",'InferredTests\Generated');
        $schema = new SimpleSchema("SubNamespaceClass");
        $schema->setNamespace('InferredTests\Generated\SubNamespace');
        $generator->generate($schema);

        $this->assertTrue(file_exists(__DIR__ . "/../classes/Generated/SubNamespace"),"Subnamespace folder doesn't exist");
        $this->assertTrue(file_exists(__DIR__ . "/../classes/Generated/SubNamespace/SubNamespaceClass.php"), "Generated file isn't created.");
        $obj = new InferredTests\Generated\GeneratorNamespacedClass();
    }
    public function testGeneratorNamespace()
    {
        if (file_exists(__DIR__ . '/../classes/Generated/GeneratorNamespacedClass.php')) {
            unlink(__DIR__ . '/../classes/Generated/GeneratorNamespacedClass.php');
        }
        $this->assertFileDoesNotExist(__DIR__ . "/../classes/Generated/GeneratorNamespacedClass.php");
        $generator = new Inferred\Generator(__DIR__ . "/../classes/Generated",'InferredTests\Generated');
        $schema = new SimpleSchema("GeneratorNamespacedClass");
        $generator->generate($schema);

        $this->assertTrue(file_exists(__DIR__ . "/../classes/Generated/GeneratorNamespacedClass.php"), "Generated file isn't created.");

        $obj = new InferredTests\Generated\GeneratorNamespacedClass();

    }
}
