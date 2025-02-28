<?php
declare(strict_types=1);
namespace Modules\Workflow\DataBuses;

use Illuminate\Database\Eloquent\Model;

class DataBusResource implements Resource
{
    public function getData(mixed $name, mixed $value, Model $model, DataBus $dataBus)
    {
        return $dataBus->data[$value];
    }

    public static function checkCondition(Model $element, DataBus $dataBus, string $field, string $operator, string $value)
    {
        return match ($operator) {
            'equal' => $dataBus->data[$dataBus->data[$field]] == $value,
            'not_equal' => $dataBus->data[$dataBus->data[$field]] != $value,
            default => true,
        };
    }

    public static function getValues(Model $element, $value, $field)
    {
        return $element->getParentDataBusKeys();
    }

    public static function loadResourceIntelligence(Model $element, $value, $field)
    {
        $fields = self::getValues($element, $value, $field);

        return view('mail::backend.workflows.fields.data_bus_resource_field', [
            'fields' => $fields,
            'value' => $value,
            'field' => $field,
        ])->render();
    }
}
