<?php declare(strict_types=1);

namespace Inferred\Tools;

use ReflectionClass;
use ReflectionException;

class BodyReader
{
    protected ReflectionClass $reflectedClass;
    /**
     * @var string[]
     */
    protected array $fileLines;

    /**
     * @throws ReflectionException
     */
    public function __construct(string $className)
    {
        $this->reflectedClass = new ReflectionClass($className);
        $filePath = $this->reflectedClass->getFileName();
        $file = file_get_contents($filePath);
        $this->fileLines = explode("\n", $file);
    }

    /**
     * @throws ReflectionException
     */
    public function getMethodBody(string $name): string
    {
        $method = $this->reflectedClass->getMethod($name);

        //getting method body
        $from = $method->getStartLine();
        $to = $method->getEndLine();
        $methodLines = array_slice($this->fileLines, $from, $to - $from);

        //Removing brackets
        //First bracket can be missing if it takes place on the same line as the method declaration
        $trimmedLine = ltrim($methodLines[0]);
        if ($trimmedLine[0] == "{") {
            array_shift($methodLines);
            $trimmedLine = ltrim($methodLines[0]);
        }
        array_pop($methodLines);

        //Trimming indentations
        //We need to calculate indentation of the first line, then remove exactly that indentation from other lines
        //If we simply trim everything, then for / if and other indentation will be trimmed as well.
        $trimLength = strlen($methodLines[0]) - strlen($trimmedLine);
        $toTrim = substr($methodLines[0], 0, $trimLength);
        $trim = function (string $el) use ($toTrim) {
            if (str_starts_with($el, $toTrim)) {
                return substr($el, strlen($toTrim));
            }
            return $el;
        };
        $trimmedLines = array_map($trim, $methodLines);
        $body = join("\n", $trimmedLines);
        return $body;
    }

    public function getNamespaces(): array
    {
        //Getting used namespaces
        $trimmedFileLines = array_map('trim', $this->fileLines);
        $filter = function (string $el) {
            if (str_starts_with(strtolower($el), "use")) {
                return true;
            }
            return false;
        };
        $namespaceLines = array_filter($trimmedFileLines, $filter);
        $trimNamespaces = function (string $el) {
            $el = str_replace(";", '', $el);
            $el = substr($el, strlen("use"));
            $el = trim($el);
            return $el;
        };
        $namespaces = array_map($trimNamespaces, $namespaceLines);
        return $namespaces;
    }
}
