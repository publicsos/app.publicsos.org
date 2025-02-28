<?php
declare(strict_types=1);
namespace Modules\Mail\Interfaces;

use Modules\Mail\Services\Messages\MessageTrackingOptions;

interface MailAdapterInterface
{
    /**
     * Send an email.
     *
     * @param string $fromEmail
     * @param string $fromName
     * @param string $toEmail
     * @param string $subject
     * @param MessageTrackingOptions $trackingOptions
     * @param string $content
     *
     * @return string
     */
    public function send(string $fromEmail, string $fromName, string $toEmail, string $subject, MessageTrackingOptions $trackingOptions, string $content): string;
}
