<?php
declare(strict_types=1);

namespace Modules\Mail\Adapters;

use Illuminate\Support\Arr;
use Modules\Mail\Services\Messages\MessageTrackingOptions;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\Dsn;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

use Modules\Mail\Adapters\Transport\CustomSmtpTransportFactory;

class ZeptomailAdapter extends BaseMailAdapter
{
    /** @var Mailer */
    protected $client;

    /** @var EsmtpTransport */
    protected $transport;

    public function send(string $fromEmail, string $fromName, string $toEmail, string $subject, MessageTrackingOptions $trackingOptions, string $content): string
    {
        $message = $this->resolveMessage($subject, $content, $fromEmail, $fromName, $toEmail);

        $this->resolveClient()->send($message);

        return $this->resolveMessageId(200);
    }

    protected function resolveClient(): Mailer
    {
        if ($this->client) {
            return $this->client;
        }

        $this->client = new Mailer($this->customTransport());

        return $this->client;
    }

    protected function resolveTransport(): EsmtpTransport
    {
        if ($this->transport) {
            return $this->transport;
        }

        // Use the custom factory
        $factory = new CustomSmtpTransportFactory();

        $encryption = Arr::get($this->config, 'encryption');

        $scheme = !is_null($encryption) && $encryption === 'tls'
            ? ((Arr::get($this->config, 'port') == 465) ? 'smtps' : 'smtp')
            : 'smtp';

        $dsn = new Dsn(
            $scheme,
            config('mail.mailers.smtp.host') ?? "localhost",
            config('mail.mailers.smtp.username') ?? "admin",
            config('mail.mailers.smtp.password') ?? "admin",
            config('mail.mailers.smtp.port') ?? 25,
            []
        );

        // Create the custom transport
        $this->transport = $factory->create($dsn);

        return $this->transport;
    }


    protected function customTransport(): EsmtpTransport
    {
        if ($this->transport) {
            return $this->transport;
        }

        $factory = new CustomSmtpTransportFactory();

        $encryption = Arr::get($this->config, 'encryption');

        $scheme = !is_null($encryption) && $encryption === 'tls'
            ? ((Arr::get($this->config, 'port') == 465) ? 'smtps' : 'smtp')
            : 'smtp';

        $dsn = new Dsn(
            $scheme,
            config('mail.mailers.smtp.host') ?? "localhost",
            config('mail.mailers.smtp.username') ?? "admin",
            config('mail.mailers.smtp.password') ?? "admin",
            config('mail.mailers.smtp.port') ?? 25,
            []
        );

        $this->transport = $factory->create($dsn);

        return $this->transport;
    }


    protected function resolveMessage(string $subject, string $content, string $fromEmail, string $fromName, string $toEmail): Email
    {
        $trackingId = uniqid('track_', true);

        $email = (new Email())
            ->returnPath(new Address($fromEmail, $fromName))
            ->replyTo(new Address($fromEmail, $fromName))
            ->from(new Address($fromEmail, $fromName))
            ->to($toEmail)
            ->subject($subject)
            ->html($content);

        // Add tracking headers properly
        $email->getHeaders()->addTextHeader('X-Mailer', self::class);
        $email->getHeaders()->addTextHeader('X-Tracking-ID', $trackingId);
        $email->getHeaders()->addTextHeader('X-Sent-By', config('app.name') ?? "Laravel Mail");

        return $email; // Ensure we return the Email object
    }

    protected function resolveMessageId($result): string
    {
        return ($result instanceof SentMessage) ? $result->getMessageId() : '-1';
    }
}
