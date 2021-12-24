<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class GeneratorTest extends TestCase
{
    public function testGeneratorCreatesFile()
    {
        unlink(__DIR__ . "/generated/TestClass.php");
        $this->assertFileDoesNotExist(  __DIR__ . "/generated/TestClass.php");
        $generator = new Inferred\Generator(__DIR__ . "/generated");
        $schema = new Inferred\Schema("TestClass");
        $schema->addParent($schema::class);
        $generator->generate($schema);

        $this->assertTrue(file_exists(__DIR__ . "/generated/TestClass.php"), "Generated file isn't created.");
    }
}
