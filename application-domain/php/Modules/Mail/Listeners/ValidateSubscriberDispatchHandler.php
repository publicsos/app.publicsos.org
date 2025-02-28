<?php

declare(strict_types=1);

namespace Modules\Mail\Listeners;

use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;

use Modules\Mail\Events\SubscriberAddedEvent;
use Log;

class ValidateSubscriberDispatchHandler implements ShouldQueue
{
    /** @var string */
    public $queue = 'validate-subscribers';

    /**
     * @throws Exception
     */
    public function handle(SubscriberAddedEvent $event): void
    {
       Log::info('Subscriber added', ['email' => $event->subscriber->email]);
    }
}
