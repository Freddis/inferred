<?php declare(strict_types=1);

namespace Inferred\PhpDoc;

use Inferred\Collections\TagList;

class DocComment
{
    protected ?string $description = null;
    protected TagList $tags;

    public function __construct()
    {
        $this->tags = new TagList();
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function addTag(Tag $tag): void
    {
        $this->tags->add($tag);
    }

    public function toString(): string
    {
        $lines = [];
        $descriptionLines = $this->description ? explode("\n", $this->description) : [];
        foreach ($descriptionLines as $line) {
            $lines[] = $line;
        }
        if (count($this->tags)) {
            $lines[] = "";
        }
        foreach ($this->tags as $tag) {
            $lines[] = $tag->toString();
        }

        return join("\n", $lines);
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getTags() : TagList
    {
        return $this->tags;
    }

    public function clearTags() : void {
        $this->tags = new TagList();
    }
}
