<?php
declare(strict_types=1);
namespace Modules\Mail\Fields;

class DropdownField implements FieldInterface
{
    public $options;

    public function __construct(array $options)
    {
        $this->options = $options;
    }

    public static function make(array $options)
    {
        return new self($options);
    }

    public function render($element, $value, $field)
    {
        return view('mail::backend.workflows.fields.dropdown_field', [
            'field' => $field,
            'value' => $value,
            'options' => $this->options,
        ])->render();
    }
}
