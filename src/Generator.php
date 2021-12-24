<?php declare(strict_types=1);

namespace Inferred;
class Generator
{
    protected string $path;

    public function __construct(string $directoryPath)
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
        $lines = [];
        $lines[] = "<?php";
        $lines[] = "\n";
        $lines[] = "class {$schema->getName()}";
        if ($schema->getParent()) {
            $lines[count($lines) - 1] .= " extends {$schema->getParent()}";
        }
        $lines[] .= "{";
        $lines[] = "\n";
        $lines[] .= "}";

        $code = join("\n",$lines);
        file_put_contents($filepath,$code);
    }

}
