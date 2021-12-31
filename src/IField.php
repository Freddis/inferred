<?php declare(strict_types=1);

namespace Inferred;

use Inferred\PhpDoc\DocComment;
use Inferred\Types\ITypeSchema;
use Inferred\Types\Visibility;

interface IField
{
    public function getName(): string;

    public function setName(string $name);

    public function setType(ITypeSchema $type): void;

    public function getType(): ?ITypeSchema;

    public function setDocComment(DocComment $comment): void;

    public function getDocComment(): ?DocComment;

    public function isStatic(): bool;

    public function setIsStatic(bool $isStatic): void;

    public function getVisibility(): Visibility;

    public function setVisibility(Visibility $visibility): void;

}
