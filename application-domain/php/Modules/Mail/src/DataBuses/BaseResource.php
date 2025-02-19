<?php
declare(strict_types=1);
namespace LaravelCompany\Mail\DataBuses;

use Illuminate\Database\Eloquent\Model;
use LaravelCompany\Mail\DataBuses\DataBus;

class BaseResource implements Resource
{
    public function getData(mixed $name, mixed $value, Model $model, DataBus $dataBus)
    {
        return config($value);
    }

    public static function getValues(Model $element, mixed $value, mixed $field)
    {
        return [
            'value' => $value,
            'field' => $field,
        ];
    }

    public static function loadResourceIntelligence(Model $element, $value, $field)
    {
        if ($element->inputField($field)) {
            return $element->inputField($field)->render($field, $value);
        }

        return view('laravel-mail::workflows.fields.text_field', [
            'value' => $value,
            'field' => $field,
        ])->render();
    }
}
