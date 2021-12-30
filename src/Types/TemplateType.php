<?php declare(strict_types=1);

namespace Inferred\Types;

class TemplateType
{

    private string $templateType;
    private string $substituteType;

    public function __construct(string $templateType, string $substituteType)
    {
        $this->templateType = $templateType;
        $this->substituteType = $substituteType;
    }

    public function getTemplateType() : string
    {
        return $this->templateType;
    }

    public function getSubstituteType() : string {
        return $this->substituteType;
    }
}
