<?php declare(strict_types=1);

namespace Inferred;

use Exception;
use Inferred\Collections\StringList;
use Nette\PhpGenerator\ClassType;

class Generator
{
    protected string $path;
    protected ?string $namespace;

    public function __construct(string $directoryPath, ?string $namespace = null)
    {
        if (!file_exists($directoryPath) || !is_writable($directoryPath)) {
            throw new Exception("Path '$directoryPath' doesn't exist or not writable.");
        }
        $this->path = $directoryPath;
        $this->namespace = $namespace;
    }

    public function generate(Schema $schema)
    {
        $class = new ClassType($schema->getName());
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
                    $newParameter->setDefaultValue($parameterSchema->getDefaultValue()->getValue());
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

        $header = $this->createFileHeader($schema);
        $code = $header . $class;
        $this->createFile($code, $schema);
    }

    protected function createFileHeader(Schema $schema): string
    {
        $result = new StringList();
        $opening = $this->generateOpeningPhpTag($schema);
        $result->add($opening);
        $result->add('');

        $namespace = $this->resolveFileNamespace($schema);
        if ($namespace) {
            $result->add("namespace $namespace;");
            $result->add('');
        }

        $namespaces = $schema->getUsedNamespaces();
        if (count($namespaces) > 0) {
            foreach ($namespaces as $namespace) {
                $result->add("use $namespace;");
            }
            $result->add('');
        }
        $result->add('');
        return $result->join("\n");
    }

    protected function generateOpeningPhpTag(Schema $schema): string
    {
        $result = '<?php';
        if ($schema->isStrict()) {
            $result .= ' declare(strict_types=1);';
        }
        return $result;
    }

    protected function createFile(string $code, Schema $schema)
    {
        $extraDirs = [];
        $namespace = $this->resolveFileNamespace($schema);
        if ($namespace) {
            $fromRoot = substr($namespace, strlen($this->namespace));
            $extraDirs = explode("\\", $fromRoot);
        }
        $dirPath = $this->path;
        foreach ($extraDirs as $dir) {
            $dirPath .= "/" . $dir;
            if (is_dir($dirPath) && is_writable($dirPath)) {
                continue;
            }
            if (is_dir($dirPath) && !is_writable($dirPath)) {
                throw new Exception("Directory '$dirPath' is not writable.");
            }
            mkdir($dirPath);
        }

        $filename = $schema->getName() . ".php";
        $filepath = $this->path . "/" . $filename;
        if ($extraDirs) {
            $filepath = $this->path . "/" . join("/", $extraDirs) . "/" . $filename;
        }
        if (file_exists($filepath)) {
            unlink($filepath);
        }

        file_put_contents($filepath, $code);
    }

    protected function resolveFileNamespace(Schema $schema): ?string
    {
        $namespace = $schema->getNamespace();
        //if schema doesn't have a namespace, use generator's namespace
        if (!$namespace) {
            $namespace = $this->namespace;
        }

        //Generator has to have namespace, since there might be many schemas and there will be conflicts
        if ($namespace && !$this->namespace) {
            throw new Exception("Generator has to have a namespace in order to use namespaces in schemas. Schemas namespaces must match the Generator namespace.");
        }

        if ($namespace) {
            if (!str_starts_with($namespace, $this->namespace)) {
                throw new Exception("Schema namespace doesn't match Generator namespace.");
            }
        }
        return $namespace;
    }
}
