<?php declare(strict_types=1);

use Inferred\Types\T1;
use Inferred\Types\TemplateType;
use Inferred\Tools\Templates\TypeCasterTemplate;
use InferredTests\User\SomeClass;
use PHPUnit\Framework\TestCase;

class DocCommentTest extends TestCase
{

    public function testDocComments(){
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
        $this->assertNotFalse($reflected->getDocComment(),"Class comment is missing");
        $this->assertStringContainsString("Implements downcast for php. Allows IDE to infer methods and fields inferred from the variable.",$reflected->getDocComment(),"Class comment is wrong");

        $methodComment = $reflected->getMethod("getValue")->getDocComment();
        $this->assertNotFalse($methodComment,"Method comment is missing");
        $this->assertStringContainsString("SomeClass",$methodComment,"Method comment is wrong");

        $fieldComment = $reflected->getProperty("value")->getDocComment();
        $this->assertNotFalse($fieldComment,"Field comment is missing");
        $this->assertStringContainsString("SomeClass",$fieldComment,"Field comment is wrong");
    }
}
