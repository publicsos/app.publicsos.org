<?php

namespace Modules\Mail\Services\Templates\Contracts;

use Modules\Mail\Services\Templates\Saloon\DTO\TemplateDTO;
use Illuminate\Support\Collection;

interface TemplateContract
{
    public function getTemplates():Collection;

    public function getTemplate(string $templateName):string;

    //todo: add import template
    public function importTemplate(TemplateDTO $templateDTO):void;
}
