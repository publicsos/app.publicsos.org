<?php
declare(strict_types=1);
namespace Modules\Mail\Services\Inbox;
use Illuminate\Support\Collection;
use Modules\Mail\DTO\MessageDTO;


interface MailContract
{
    public function getFolders(): Collection;
    public function getMessages(string $folder = 'INBOX'): Collection;
    public function getMessage(string $folder, string $uid): ?MessageDTO;
}
