<?php
declare(strict_types=1);
namespace Modules\Mail\Triggers;


use Modules\Mail\Fields\DropdownField;
use Modules\Mail\Models\Campaign;
use Modules\Mail\Models\Message;
use Modules\Mail\Models\Subscriber;
use Modules\Mail\Models\Template;

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
                        Message::class => 'Message',
                        Campaign::class => 'Campaign',
                        Template::class => 'Template',
                    ]),
            'event' => DropdownField::make($this->classEvents),
        ];

    }
}
