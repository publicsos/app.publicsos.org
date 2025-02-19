<?php
declare(strict_types=1);
namespace LaravelCompany\Mail\View\Components;

use Illuminate\View\Component;

class SelectWithSearch extends Component
{
    public $name;
    public $label;
    public $options;
    public $multiple;

    public function __construct($name, $label = null, $options = [], $multiple = false)
    {
        $this->name = $name;
        $this->label = $label;
        $this->options = $options;
        $this->multiple = $multiple;
    }

    public function isSelected($key): bool
    {
        if (old($this->name)) {
            return in_array($key, (array) old($this->name));
        }

        return false;
    }

    public function render()
    {
        return view('laravel-mail::components.select-with-search');
    }
}
