




















































<?php

use App\Models\User;
use Modules\Mail\Loggers\WorkflowLog;
use Modules\Mail\Models\Message;
use Modules\Mail\Models\Subscriber;
use Modules\Mail\Tasks\RssReader;

return [

    /*

    |--------------------------------------------------------------------------
    | Styling TODO - MOVE THIS to laravel-mail.config
    |--------------------------------------------------------------------------
    |
    | To easily integrate the Workflow frontend to your Style you can set your layout and the section.
    |
    */
    'layout' => 'mail::backend.layouts.workflow_app',

    'section' => 'content',

    'unlayer' => env('UNLAYER_API_KEY'),
    /*
    |--------------------------------------------------------------------------
    | Tasks
    |--------------------------------------------------------------------------
    |
    | Here you can register all the Tasks which should be used in the Workflow Package. You can also deactivate Tasks
    | just by deleting them here.
    |
    */
    'tasks' => [
        'SendMail' => Modules\Workflow\Tasks\SendMail::class,
        'Execute' => Modules\Workflow\Tasks\Execute::class,
        'PregReplace' => Modules\Workflow\Tasks\PregReplace::class,
        'HtmlInput' => Modules\Workflow\Tasks\HtmlInput::class,
        'DomPDF' => Modules\Workflow\Tasks\DomPDF::class,
        'HttpStatus' => Modules\Workflow\Tasks\HttpStatus::class,
        'LoadModel' => Modules\Workflow\Tasks\LoadModel::class,
        'ChangeModel' => Modules\Workflow\Tasks\ChangeModel::class,
        'SaveModel' => Modules\Workflow\Tasks\SaveModel::class,
        'TextInput' => Modules\Workflow\Tasks\TextInput::class,
    ],

    'task_settings' => [
        'LoadModel' => [
            'classes' => [
                User::class => 'User',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Data Resources
    |--------------------------------------------------------------------------
    |
    | Here you can register all the Data Resources which should be used in the Workflow Package. You can also
    | deactivate Data Resources just by deleting them here.
    |
    */
    'data_resources' => [
        'ValueResource' => Modules\Workflow\DataBuses\ValueResource::class,
        'ModelResource' => Modules\Workflow\DataBuses\ModelResource::class,
        'DataResource' => Modules\Workflow\DataBuses\DataBusResource::class,
        'ConfigResource' => Modules\Workflow\DataBuses\ConfigResource::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Triggers
    |--------------------------------------------------------------------------
    |
    | Here you can register all the Triggers which should be used in the Workflow Package. You can also
    | deactivate Triggers just by deleting them here.
    |
    | Observers
    |
    | Events:
    | You can register all the events the Trigger should listen to here.
    |
    | Classes:
    | You can register the Classes which can be used for the ObserverTrigger.
    |
    */
    'triggers' => [

        'types' => [
            'ObserverTrigger' => Modules\Workflow\Triggers\ObserverTrigger::class,

        ],

        'Observers' => [
            'events' => [
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
            ],
            'classes' => [
                User::class => 'User',
                Subscriber::class => 'Subscriber',
                Message::class => 'Message',
            ],
        ],
        'Button' => [
            'classes' => [
                User::class => 'User',
                Subscriber::class => 'Subscriber',
                Message::class => 'Message',
            ],
            'categories' => [
                'all' => 'All',
            ],
        ],

    ],
    'queue' => 'redis',

    /*
    |--------------------------------------------------------------------------
    | Routes
    |--------------------------------------------------------------------------
    |
    | Configure if the package should load it's default routes. Default its not using the default routes. We recommend
    | using them as described in the Documentation because you should put a Auth middleware on them.
    */
    'prefix' => 'workflows',

    /*
    |--------------------------------------------------------------------------
    | Database prefixing
    |--------------------------------------------------------------------------
    |
    | We know how annoying it can be if a package brings a table name into your system which you are even worse another
    | package all ready uses. With the db_prefix you can set a prefix to the tables to avoid this conflict.
    | This changes needs to be done before the Migrations are running.
    */
    'db_prefix' => '',
];
