<?php
declare(strict_types=1);
namespace Modules\Workflow\Fields;

interface FieldInterface
{
    public function render($element, $value, $field);
}
