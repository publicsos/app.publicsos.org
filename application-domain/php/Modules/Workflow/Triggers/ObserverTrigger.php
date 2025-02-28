<?php
declare(strict_types=1);
namespace Modules\Workflow\Triggers;


use Modules\Workflow\Fields\DropdownField;
use Modules\Workflow\Models\Campaign;
use Modules\Workflow\Models\Message;
use Modules\Mail\Models\Subscriber;
use Modules\Workflow\Models\Template;

class ObserverTrigger extends Trigger
{
    public static $icon = '<i class="fas fa-binoculars"></i>';

    private array $classEvents = [
        'retrieved',
        'creating',
        'created',
        'updating',
        'updated',
        'saving',
        'saved',
        'deleting',
        'deleted',
        'restoring',
        'restored',
        'forceDeleted',
    ];

    public static $fields = [
        'Class' => 'class',
        'Event' => 'event',
    ];

    public function inputFields(): array
    {
        return [
            'class' => DropdownField::make( [

                        Subscriber::class => 'Subscriber',

                    ]),
            'event' => DropdownField::make($this->classEvents),
        ];

    }
}
