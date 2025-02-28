<?php

namespace Modules\Mail\Triggers;

use Illuminate\Database\Eloquent\Model;

trait WorkflowObservable
{
    public static function bootWorkflowObservable(): void
    {
        static::created(function (Model $model) {
            self::startWorkflows($model, 'created');
        });

    }

    public static function getRegisteredTriggers(string $class, string $event)
    {
        $class_array = explode('\\', $class);

        $className = $class_array[count($class_array) - 1];

        return Trigger::where('type', ObserverTrigger::class)
            ->where('data_fields->class->value', 'like', '%'.$className.'%')
            ->where('data_fields->event->value', $event)
            ->get();
    }

    public static function startWorkflows(Model $model, string $event)
    {

    }
}
