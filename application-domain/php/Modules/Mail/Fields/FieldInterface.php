<?php
declare(strict_types=1);
namespace Modules\Mail\Fields;

interface FieldInterface
{
    public function render($element, $value, $field);
}
