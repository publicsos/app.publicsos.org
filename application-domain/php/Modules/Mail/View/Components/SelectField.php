<?php

declare(strict_types=1);

namespace Modules\Mail\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class SelectField extends Component
{
    public string $name;
    public string $label;
    public array|Collection $options;
    public mixed $value;
    public bool $multiple;
    public ?string $placeholder;

    /**
     * Create the component instance.
     *
     * @param string $name Field name attribute
     * @param string $label Field label text
     * @param array|Collection $options Select options as key-value pairs
     * @param mixed $value Selected value(s)
     * @param bool $multiple Whether multiple selections are allowed
     * @param string|null $placeholder Placeholder text for the select
     */
    public function __construct(
        string $name,
        string $label = '',
        array|Collection $options = [],
        mixed $value = null,
        bool $multiple = false,
        ?string $placeholder = null
    ) {
        $this->name = $name;
        $this->label = $label;
        $this->options = $options instanceof Collection ? $options->toArray() : $options;
        $this->value = $this->normalizeValue($value);
        $this->multiple = $multiple;
        $this->placeholder = $placeholder;
    }

    /**
     * Check if an option is selected
     */
    public function isSelected(string|int $key): bool
    {
        if ($this->multiple) {
            $values = is_array($this->value) ? $this->value : [$this->value];
            return in_array($key, $values, true);
        }

        return $key === $this->value;
    }

    /**
     * Generate unique ID for the field
     */
    public function getId(): string
    {
        return 'id-field-' . str_replace(['[]', '[', ']'], ['', '-', ''], $this->name);
    }

    /**
     * Get CSS classes for the select element
     */
    public function getClasses(): string
    {
        $classes = ['form-control'];

        if ($this->multiple) {
            $classes[] = 'selectpicker';
        }

        return implode(' ', $classes);
    }

    /**
     * Normalize the value to a consistent format
     */
    private function normalizeValue(mixed $value): mixed
    {
        if ($value instanceof Collection) {
            return $value->toArray();
        }

        return $value;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('mail::backend.components.select-field');
    }
}
