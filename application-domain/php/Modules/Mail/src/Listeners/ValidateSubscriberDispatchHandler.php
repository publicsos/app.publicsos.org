<?php

declare(strict_types=1);

namespace LaravelCompany\Mail\Listeners;

use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;

use LaravelCompany\Mail\Events\SubscriberAddedEvent;
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
