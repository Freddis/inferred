<?php declare(strict_types=1);

use InferredTests\User\SimpleSchema;
use PHPUnit\Framework\TestCase;

class GeneratorTest extends TestCase
{
    public function testGeneratorCreatesFile()
    {
        if (file_exists(__DIR__ . '/../generated/TestClass.php')) {
            unlink(__DIR__ . '/../generated/TestClass.php');
        }
        $this->assertFileDoesNotExist(__DIR__ . "/../generated/TestClass.php");
        $generator = new Inferred\Generator(__DIR__ . "/../generated");
        $schema = new SimpleSchema("TestClass");
        $schema->addParent($schema::class);
        $generator->generate($schema);

        $this->assertTrue(file_exists(__DIR__ . "/../generated/TestClass.php"), "Generated file isn't created.");
    }
}
