<?php
declare(strict_types=1);
namespace LaravelCompany\Mail\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use LaravelCompany\Mail\Adapters\BaseMailAdapter;
use LaravelCompany\Mail\Factories\MailAdapterFactory;
use LaravelCompany\Mail\Interfaces\QuotaServiceInterface;
use LaravelCompany\Mail\Models\EmailService;
use LaravelCompany\Mail\Models\EmailServiceType;

/**
 * TODO IMPROVE THIS CLASS
 */
class QuotaService implements QuotaServiceInterface
{
    public function exceedsQuota(EmailService $emailService, int $messageCount): bool
    {
        switch ($emailService->type_id) {
            case EmailServiceType::SES:
                return $this->exceedsSesQuota($emailService, $messageCount);

            case EmailServiceType::SENDGRID:
                return $this->exceedsSendGridQuota($messageCount);

            case EmailServiceType::MAILGUN:
            case EmailServiceType::POSTMARK:
            case EmailServiceType::MAILJET:
            case EmailServiceType::SMTP:
                return $this->exceedSmtpQuota($messageCount);

            case EmailServiceType::POSTAL:
            case EmailServiceType::ZEPTO:
                return false;
        }

        throw new \DomainException('Unrecognized email service type');
    }

    protected function resolveMailAdapter(EmailService $emailService): BaseMailAdapter
    {
        return app(MailAdapterFactory::class)->adapter($emailService);
    }

    protected function exceedsSesQuota(EmailService $emailService, int $messageCount): bool
    {
        $mailAdapter = $this->resolveMailAdapter($emailService);


        $quota = $mailAdapter->getSendQuota();

        if (empty($quota)) {
            Log::error(
                'Failed to fetch quota from SES',
                [
                    'email_service_id' => $emailService->id,
                ]
            );

            return false;
        }

        $limit = Arr::get($quota, 'Max24HourSend');

        // -1 signifies an unlimited quota
        if ($limit === -1) {
            return false;
        }

        $sent = Arr::get($quota, 'SentLast24Hours');

        $remaining = (int)floor($limit - $sent);

        return $messageCount > $remaining;
    }

    protected function exceedsSendGridQuota(int $messageCount): bool
    {
        $quota = [
            'Max24HourSend' => config('services.sendgrid.limit_per_day'),
            'SentLast24Hours' => 0
        ];

        $limit = Arr::get($quota, 'Max24HourSend');

        if ($limit === -1) {
            return false;
        }

        $sent = Arr::get($quota, 'SentLast24Hours');

        $remaining = (int)floor($limit - $sent);

        return $messageCount > $remaining;
    }


    public function exceedSmtpQuota(int $messageCount): bool
    {
        $quota = [
            'Max24HourSend' => config('services.smtp.limit_per_day'),
            'SentLast24Hours' => 0
        ];

        $limit = Arr::get($quota, 'Max24HourSend');

        if ($limit === -1) {
            return false;
        }

        $sent = Arr::get($quota, 'SentLast24Hours');

        $remaining = (int)floor($limit - $sent);

        return $messageCount > $remaining;
    }
}
