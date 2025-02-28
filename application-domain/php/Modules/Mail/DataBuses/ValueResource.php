<?php
declare(strict_types=1);
namespace Modules\Mail\DataBuses;

use Illuminate\Database\Eloquent\Model;


class ValueResource implements Resource
{

    public function __construct()
    {
        info('Constructing Value Resource');
    }

    public function getData(mixed $name, mixed $value, Model $model, DataBus $dataBus)
    {
        info("The value resource data for {$name} is {$value}");
        return $value;
    }

    public static function getValues(Model $element, $value, $field)
    {
        return [
            'value' => $value,
            'field' => $field,
        ];
    }

    public static function checkCondition(Model $element, DataBus $dataBus, string $field, string $operator, string $value)
    {
        return match ($operator) {
            'equal' => $dataBus->get($field) == $value,
            'not_equal' => $dataBus->get($field) != $value,
            default => true,
        };
    }

    public static function loadResourceIntelligence(Model $element, mixed $value, mixed $field_name):string
    {
        info('Loading Resource Intelligence for '.$field_name);

        if ($element->inputField($field_name)) {
            return $element->inputField($field_name)->render($element, $value, $field_name);
        }

        return view('mail::backend.workflows.fields.text_field', [
            'value' => $value,
            'field' => $field_name,
        ])->render();
    }
}
