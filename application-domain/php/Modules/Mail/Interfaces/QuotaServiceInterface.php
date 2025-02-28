<?php
declare(strict_types=1);
namespace Modules\Mail\Interfaces;

use Modules\Mail\Models\EmailService;

interface QuotaServiceInterface
{
    public function exceedsQuota(EmailService $emailService, int $messageCount): bool;
}
