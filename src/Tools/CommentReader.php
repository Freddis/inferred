<?php declare(strict_types=1);

namespace Inferred\Tools;

use Inferred\Collections\TagList;
use Inferred\PhpDoc\DocComment;
use Inferred\PhpDoc\SimpleTag;
use Inferred\PhpDoc\Tag;

class CommentReader
{
    protected array $lines;

    public function __construct(string $body)
    {
        $this->lines = explode("\n", $body);
    }

    public function getDocComment(): DocComment
    {
        $comment = new DocComment();
        $description = $this->parseDescription();
        if ($description) {
            $comment->setDescription($description);
        }
        $tags = $this->parseTags();
        foreach($tags as $tag){
            $comment->addTag($tag);
        }
        return $comment;
    }

    protected function parseDescription(): ?string
    {
        $trimmed = $this->trimLines($this->lines);
        $description = [];
        foreach ($trimmed as $line) {
            if ($this->doesLineHasTag($line)) {
                break;
            }
            if($line == ""){
                continue;
            }
            $description[] = $line;
        }
        if (!$description) {
            return null;
        }
        $result = join("\n", $description);
        return $result;
    }

    private function trimLines(array $lines): array
    {
        $trimmed = array_map("trim", $lines);
        $clearStars = function ($el) {
            if (str_starts_with($el, "/**")) {
                $el = substr($el, strlen("/**"));
            }
            if (str_ends_with($el, '*/')) {
                $el = substr($el, 0, strlen($el) - strlen("*/"));
            }
            if (str_starts_with($el, "*")) {
                $el = substr($el, 1);
            }
            return trim($el);
        };
        $cleared = array_map($clearStars, $trimmed);
        return $cleared;
    }

    protected function doesLineHasTag(string $line): bool
    {
        if (str_starts_with(trim($line), "@")) {
            return true;
        }
        return false;
    }

    protected function parseTags() : TagList
    {
        $tagList = new TagList();
        $trimmed = $this->trimLines($this->lines);
        foreach($trimmed as $line){
            if($this->doesLineHasTag($line)){
                $tag = $this->parseTag($line);
                $tagList->add($tag);
            }
        }
        return $tagList;
    }

    private function parseTag(string $line) : Tag
    {
        $parts = explode(" ",$line);
        $name = substr($parts[0],1);
        $otherParts = array_slice($parts,1);
        $description = trim(join(" ",$otherParts));
        $tag = new SimpleTag($name,$description);
        return $tag;
    }

}
