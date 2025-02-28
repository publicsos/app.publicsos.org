<?php
declare(strict_types=1);
namespace Modules\Mail\Adapters;

use Illuminate\Support\Arr;
use Modules\Mail\Services\Messages\MessageTrackingOptions;

/**
 * program has been create
 * Send it using the custom go program. that has been created
 */
class LocalAdapter extends BaseMailAdapter
{
   /**
     * @throws TypeException
     * @throws \Throwable
     */
    public function send(string $fromEmail, string $fromName, string $toEmail, string $subject, MessageTrackingOptions $trackingOptions, string $content): string
    {
        return "not implemented";
    }

}
