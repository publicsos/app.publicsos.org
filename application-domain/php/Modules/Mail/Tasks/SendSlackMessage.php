<?php
declare(strict_types=1);
namespace Modules\Mail\Tasks;

use Illuminate\Support\Facades\Notification;
use Modules\Workflows\Notifications\SlackNotification;

class SendSlackMessage extends Task
{
    public static array $fields = [
        'Channel/User' => 'channel',
        'Message' => 'message',
    ];

    public static array $output = [
        'Output' => 'output',
    ];

    public static $icon = '<i class="fab fa-slack"></i>';

    public function execute(): void
    {
        $channel = $this->getData('channel');
        $message = $this->getData('message');

        Notification::route('slack', env('WORKFLOW_SLACK_CHANNEL'))
            ->notify(new SlackNotification($channel, $message));
    }
}
