<?php declare(strict_types=1);

namespace Inferred;

use Exception;
use Nette\PhpGenerator\PhpFile;

class Generator
{
    protected string $path;

    public function __construct(string $directoryPath, string $namespace = null)
    {
        if (!file_exists($directoryPath) || !is_writable($directoryPath)) {
            throw new Exception("Path '$directoryPath' doesn't exist or not writable.");
        }
        $this->path = $directoryPath;
    }

    public function generate(Schema $schema)
    {
        $filename = $schema->getName() . ".php";
        $filepath = $this->path . "/" . $filename;
        if (file_exists($filepath)) {
            unlink($filepath);
        }

        $file = new PhpFile();
        $file->setStrictTypes();

        $class = $file->addClass($schema->getName());
        if ($schema->hasParent()) {
            $class->setExtends($schema->getParent());
        }
        $methods = $schema->getMethods();
        foreach ($methods as $methodSchema) {
            $newMethod = $class->addMethod($methodSchema->getName());
            $newMethod->setVisibility($methodSchema->getVisibility()->toString());
            $newMethod->setAbstract($methodSchema->isAbstract());
            if (!$methodSchema->isAbstract()) {
                $newMethod->setBody($methodSchema->getBody());
            }

            if ($methodSchema->getReturnType()) {
                $newMethod->setReturnType($methodSchema->getReturnType()->toString());
            }
            $parameters = $methodSchema->getParameters();
            foreach ($parameters as $parameterSchema) {
                $newParameter = $newMethod->addParameter($parameterSchema->getName());

                if ($parameterSchema->getType()) {
                    $newParameter->setType($parameterSchema->getType()->toString());
                    $newParameter->setNullable($parameterSchema->getType()->isNullable());
                }
                if ($parameterSchema->hasDefaultValue()) {
                    $newParameter->setDefaultValue($parameterSchema->getDefaultValue()->toString());
                }
            }
        }

        $fields = $schema->getFields();
        foreach ($fields as $field) {
            $prop = $class->addProperty($field->getName());
            $prop->setVisibility($field->getVisibility()->toString());
            if ($field->getType()) {
                $prop->setType($field->getType()->toString());
                $prop->setNullable($field->getType()->isNullable());
            }
            if ($field->getDefaultValue()) {
                $prop->setValue($field->getDefaultValue()->getValue());
            }
        }

        $code = (string)$file;
        file_put_contents($filepath, $code);
    }

}
