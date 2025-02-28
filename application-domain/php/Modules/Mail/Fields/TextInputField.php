<?php

namespace Modules\Mail\Fields;

use Modules\Mail\DataBuses\DataBusResource;

use Modules\Mail\DataBuses\ModelResource;

class TextInputField implements FieldInterface
{
    public $options;


    public static function make()
    {
        return new self();
    }

    public function render($element, $value, $field)
    {
        $placeholders = collect([
            'data_bus' => DataBusResource::getValues($element, $value, $field),
            'model' => ModelResource::getValues($element, $value, $field),
        ]);

        // Process placeholders using collection methods
        $placeholders = $placeholders->map(function ($values, $key) {
            return collect($values)->map(function ($value) use ($key) {
                if ($key === 'data_bus') {
                    return '$dataBus->get(\\\'' . $value . '\\\')';
                }
                return '$model->' . $value;
            });
        });

        return view('mail::backend.workflows.fields.text_input_field', [
            'field' => $field,
            'value' => $value,
            'placeholders' => $placeholders->toArray(),
        ])->render();
    }

}
