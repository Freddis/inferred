<?php declare(strict_types=1);

namespace Inferred;

use Inferred\Collections\FieldSchemaList;
use Inferred\Collections\MethodSchemaList;
use Inferred\Collections\TemplateTypeList;
use Inferred\Tools\BodyReader;
use Inferred\Types\ITypeSchema;
use Inferred\Types\ParameterSchema;
use Inferred\Types\TypeSchema;
use Inferred\Types\Visibility;
use Inferred\Values\DefaultValue;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionParameter;
use ReflectionProperty;

class TemplateSchema extends Schema
{
    protected ReflectionClass $reflectedObject;
    protected TemplateTypeList $templateTypes;
    protected BodyReader $bodyReader;

    /**
     * @throws ReflectionException
     */
    public function __construct(string $class, ?string $name = null)
    {
        parent::__construct($name);
        $this->reflectedObject = new ReflectionClass($class);
        $this->templateTypes = new TemplateTypeList();
        $this->bodyReader = new BodyReader($class);
    }

    public function addTemplateType(Types\TemplateType $type): void
    {
        $this->templateTypes->add($type);
    }

    public function getMethods(): MethodSchemaList
    {
        $result = new MethodSchemaList();
        $methods = $this->reflectedObject->getMethods();
        foreach ($methods as $method) {
            $result->add($this->createMethodSchema($method));
        }
        return $result;
    }

    protected function createMethodSchema(ReflectionMethod $method): MethodSchema
    {
        $schema = new MethodSchema($method->getName());
        $namespaces = $this->bodyReader->getNamespaces();
        $body = $this->bodyReader->getMethodBody($schema->getName());
        $replacedBody = $this->replaceTemplateTypesInBody($body, $namespaces);
        $schema->setBody($replacedBody);

        $returnType = $this->createReturnType($method, $namespaces);
        if ($returnType) {
            $schema->setReturnType($returnType);
        }
        if ($method->isProtected()) {
            $schema->setVisibility(Visibility::Protected);
        }
        if ($method->isPublic()) {
            $schema->setVisibility(Visibility::Public);
        }
        if ($method->isPrivate()) {
            $schema->setVisibility(Visibility::Private);
        }


        $parameters = $method->getParameters();
        foreach ($parameters as $parameter) {
            $parameterSchema = $this->createMethodParameterSchema($parameter);
            $schema->addParameter($parameterSchema);
        }
        return $schema;
    }

    protected function createReturnType(ReflectionMethod $method): ?ITypeSchema
    {
        $returnType = null;
        if ($method->getReturnType()) {
            $reflectedReturnType = $method->getReturnType();
            $reflectedTypeName = $reflectedReturnType->getName();
            $returnTypeName = $this->replaceTemplateTypes($reflectedTypeName);
            $returnType = new TypeSchema($returnTypeName);
            $returnType->setNullable($reflectedReturnType->allowsNull());
        }
        return $returnType;
    }

    protected function createMethodParameterSchema(ReflectionParameter $parameter): ParameterSchema
    {
        $name = $parameter->getName();
        $reflectedType = $parameter->getType();

        $type = null;
        if ($reflectedType) {
            $typeName = $this->replaceTemplateTypes($reflectedType->getName());
            $type = new TypeSchema($typeName);
            $type->setNullable($reflectedType->allowsNull());
        }

        $schema = new ParameterSchema($name, $type);
        if ($parameter->isDefaultValueAvailable()) {
            $defaultValue = $parameter->getDefaultValue();
            $schema->setDefaultValue(new DefaultValue($defaultValue));
        }
        return $schema;
    }

    protected function replaceTemplateTypesInBody(string $body, array $namespaces): string
    {
        foreach ($this->templateTypes as $template) {
            $search = [];
            $search[] = "\\" . $template->getTemplateType();
            $search[] = $template->getTemplateType();
            foreach ($namespaces as $namespace) {
                if ($namespace == $template->getTemplateType()) {
                    $parts = explode("\\", $namespace);
                    $shortName = $parts[count($parts) - 1];
                    $search[] = "\\" . $shortName;
                    $search[] = $shortName;
                    break;
                }
            }

            $body = str_replace($search, $template->getSubstituteType(), $body);
        }
        return $body;
    }

    protected function replaceTemplateTypes(string $typeName): string
    {
        foreach ($this->templateTypes as $template) {
            if ($template->getTemplateType() === $typeName) {
                return $template->getSubstituteType();
            }
        }
        return $typeName;
    }

    public function getFields(): FieldSchemaList
    {
        $result = new FieldSchemaList();
        $fields = $this->reflectedObject->getProperties();
        foreach ($fields as $field) {
            $result->add($this->createFieldSchema($field));
        }
        return $result;
    }

    protected function createFieldSchema(ReflectionProperty $property): FieldSchema
    {
        $fieldSchema = new FieldSchema($property->getName());
        if ($property->isProtected()) {
            $fieldSchema->setVisibility(Visibility::Protected);
        }
        if ($property->isPublic()) {
            $fieldSchema->setVisibility(Visibility::Public);
        }
        if ($property->isPrivate()) {
            $fieldSchema->setVisibility(Visibility::Private);
        }

        if ($property->getType()) {
            $type = new TypeSchema($property->getType()->getName());
            $type->setNullable($property->getType()->allowsNull());
            $fieldSchema->setType($type);
        }

        if ($property->hasDefaultValue()) {
            $defaultValue = new DefaultValue($property->getDefaultValue());
            $fieldSchema->setDefaultValue($defaultValue);
        }

        return $fieldSchema;
    }
}
