<?php
declare(strict_types=1);
namespace Modules\Workflow\DataBuses;

use Illuminate\Database\Eloquent\Model;


class ConfigResource implements Resource
{
    public function getData(mixed $name, mixed $value, Model $model, DataBus $dataBus)
    {
        info('Getting Data for '.$name);
        return config($value);
    }

    public static function getValues(Model $element, $value, $field)
    {
        return [];
    }

    public static function loadResourceIntelligence(Model $element, $value, $field)
    {
        if ($element->inputField($field)) {
            return $element->inputField($field)->render($field, $value);
        }

        return view('mail::backend.workflows.fields.text_field', [
            'value' => $value,
            'field' => $field,
        ])->render();
    }
}
