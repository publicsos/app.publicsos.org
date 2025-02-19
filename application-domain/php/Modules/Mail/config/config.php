<?php


use App\Models\User;
use LaravelCompany\Mail\Loggers\WorkflowLog;
use LaravelCompany\Mail\Models\Message;
use LaravelCompany\Mail\Models\Subscriber;
use LaravelCompany\Mail\Tasks\RssReader;

return [

    /*
    |--------------------------------------------------------------------------
    | Styling
    |--------------------------------------------------------------------------
    |
    | To easily integrate the Workflow frontend to your Style you can set your layout and the section.
    |
    */
    'layout' => 'laravel-mail::layouts.workflow_app',
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
        'SendMail' => LaravelCompany\Mail\Tasks\SendMail::class,
        'Execute' => LaravelCompany\Mail\Tasks\Execute::class,
        'Scan' => LaravelCompany\Mail\Tasks\Scan::class,
        'PregReplace' => LaravelCompany\Mail\Tasks\PregReplace::class,
        'HtmlInput' => LaravelCompany\Mail\Tasks\HtmlInput::class,
        'DomPDF' => LaravelCompany\Mail\Tasks\DomPDF::class,
        'HttpStatus' => LaravelCompany\Mail\Tasks\HttpStatus::class,
        'LoadModel' => LaravelCompany\Mail\Tasks\LoadModel::class,
        'ChangeModel' => LaravelCompany\Mail\Tasks\ChangeModel::class,
        'SaveModel' => LaravelCompany\Mail\Tasks\SaveModel::class,
        'SendSlackMessage' => LaravelCompany\Mail\Tasks\SendSlackMessage::class,
        'TextInput' => LaravelCompany\Mail\Tasks\TextInput::class,
        'RssReader' => LaravelCompany\Mail\Tasks\RssReader::class,
        'Campaign' => LaravelCompany\Mail\Tasks\Campaign::class,
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
        'ValueResource' => LaravelCompany\Mail\DataBuses\ValueResource::class,
        'ModelResource' => LaravelCompany\Mail\DataBuses\ModelResource::class,
        'DataResource' => LaravelCompany\Mail\DataBuses\DataBusResource::class,
        'ConfigResource' => LaravelCompany\Mail\DataBuses\ConfigResource::class,
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
            'ObserverTrigger' => LaravelCompany\Mail\Triggers\ObserverTrigger::class,
            'ButtonTrigger' => LaravelCompany\Mail\Triggers\ButtonTrigger::class,
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
