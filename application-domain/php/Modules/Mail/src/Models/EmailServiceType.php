<?php

declare(strict_types=1);

namespace LaravelCompany\Mail\Models;

class EmailServiceType extends BaseModel
{
    protected $table = 'email_service_types';

    public const SES = 1;
    public const SENDGRID = 2;
    public const MAILGUN = 3;
    public const POSTMARK = 4;
    public const MAILJET = 5;
    public const SMTP = 6;
    public const POSTAL = 7;
    public const ZEPTO = 8;
    public const PRINT = 9;
    public const FACEBOOK = 10;
    public const LINKEDIN = 11;
    public const TRACK = 12;

    ///if this increase please check seeder and update the static array
    protected static array $types = [
        self::SES => 'SES',
        self::SENDGRID => 'Sendgrid',
        self::MAILGUN => 'Mailgun',
        self::POSTMARK => 'Postmark',
        self::MAILJET => 'Mailjet',
        self::SMTP => 'SMTP',
        self::POSTAL => 'Postal',
        self::ZEPTO => 'ZeptoMail',
        self::PRINT => 'PrintMail',
        self::TRACK => 'SmtpTrack',
    ];

    /**
     * Resolve a type ID to a type name.
     */
    public static function resolve(int $typeId): ?string
    {
        return static::$types[$typeId] ?? null;
    }

    /**
     * Get the configuration settings for a specific email service type.
     */
    public static function getServiceConfig(int $typeId, array $settings = []): array
    {
        $baseConfig = match ($typeId) {
            self::SMTP => [
                'host' => config('mail.mailers.smtp.host'),
                'port' => config('mail.mailers.smtp.port'),
                'username' => config('mail.mailers.smtp.username'),
                'password' => config('mail.mailers.smtp.password'),
                'encryption' => config('mail.mailers.smtp.encryption'),
                'from' => config('mail.mailers.smtp.from'),
            ],

            self::TRACK => [
                'host' => config('mail.mailers.track.host'),
                'port' => config('mail.mailers.track.port'),
                'username' => config('mail.mailers.track.username'),
                'password' => config('mail.mailers.track.password'),
                'encryption' => config('mail.mailers.track.encryption'),
                'from' => config('mail.mailers.track.from'),
            ],

            self::SENDGRID => [
                'api_key' => config('services.sendgrid.api_key'),
                'from' => config('mail.from.address'),
            ],

            self::MAILGUN => [
                'domain' => config('services.mailgun.domain'),
                'secret' => config('services.mailgun.secret'),
                'from' => config('mail.from.address'),
            ],

            self::ZEPTO => [
                'host' => "smtp.zeptomail.com",
                'port' => "587",
                'username' => config('mail.mailers.smtp.username'),
                'password' => config('mail.mailers.smtp.password'),
                'encryption' => config('mail.mailers.smtp.encryption'),
                'from' => config('mail.mailers.smtp.from'),
            ],

            self::PRINT => [
                'hostname' => config('services.print.hostname'),
                'username' => config('services.print.username'),
                'password' => config('services.print.password'),
            ],
            // Add other service configurations as needed
            default => [],
        };

        return array_merge($baseConfig, $settings);
    }
}
