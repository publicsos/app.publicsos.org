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
        'SendMail' => Modules\Mail\Tasks\SendMail::class,
        'Execute' => Modules\Mail\Tasks\Execute::class,
        'Scan' => Modules\Mail\Tasks\Scan::class,
        'PregReplace' => Modules\Mail\Tasks\PregReplace::class,
        'HtmlInput' => Modules\Mail\Tasks\HtmlInput::class,
        'DomPDF' => Modules\Mail\Tasks\DomPDF::class,
        'HttpStatus' => Modules\Mail\Tasks\HttpStatus::class,
        'LoadModel' => Modules\Mail\Tasks\LoadModel::class,
        'ChangeModel' => Modules\Mail\Tasks\ChangeModel::class,
        'SaveModel' => Modules\Mail\Tasks\SaveModel::class,
        'SendSlackMessage' => Modules\Mail\Tasks\SendSlackMessage::class,
        'TextInput' => Modules\Mail\Tasks\TextInput::class,
        'RssReader' => Modules\Mail\Tasks\RssReader::class,
        'MagentoProducts' => Modules\Mail\Tasks\MagentoProducts::class,
        'Campaign' => Modules\Mail\Tasks\Campaign::class,
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
        'ValueResource' => Modules\Mail\DataBuses\ValueResource::class,
        'ModelResource' => Modules\Mail\DataBuses\ModelResource::class,
        'DataResource' => Modules\Mail\DataBuses\DataBusResource::class,
        'ConfigResource' => Modules\Mail\DataBuses\ConfigResource::class,
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
            'ObserverTrigger' => Modules\Mail\Triggers\ObserverTrigger::class,
            'ButtonTrigger' => Modules\Mail\Triggers\ButtonTrigger::class,
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
                WorkflowLog::class => 'WorkflowLog',
                Subscriber::class => 'Subscriber',
                Message::class => 'Message',
            ],
        ],
        'Button' => [
            'classes' => [
                User::class => 'User',
                WorkflowLog::class => 'WorkflowLog',
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
