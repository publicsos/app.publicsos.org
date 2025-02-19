<?php


declare(strict_types=1);

namespace LaravelCompany\Mail\Services\Inbox;

use Illuminate\Support\Collection;
use LaravelCompany\Mail\DTO\MessageDTO;
use LaravelCompany\Mail\DTO\MessageDTOFactory;
use Webklex\PHPIMAP\ClientManager;
use Webklex\PHPIMAP\Message;
use LaravelCompany\Mail\Services\Inbox\Exceptions\InboxException;

class MailClient implements MailContract
{
    protected ClientManager $clientManager;

    public function __construct(ClientManager $clientManager)
    {
        $this->clientManager = $clientManager;
        $this->clientManager->account('default');
        $this->clientManager->connect();
    }

    public function getFolders(): Collection
    {
        try {
            return collect($this->clientManager->getFolders())->map(fn ($folder) => [
                'name'   => $folder->name,
                'path'   => $folder->path,
                'unseen' => $folder->query()->unseen()->setFetchFlags(false)->limit(1)->count(),
            ]);
        } catch (\Throwable $e) {
            throw new InboxException('Failed to retrieve mail folders.' . $e->getMessage(), 0, $e);
        }
    }
    public function getMessages(string $folder = 'INBOX'): Collection
    {
        try {
            $oFolder = $this->clientManager->connect()->getFolder($folder);

            $messages = $oFolder->query()->all()->setFetchBody(true)->get();

            return $messages->map(fn ($message) => [
                'from'        => $message->getFrom()[0]->full ?? 'Unknown',
                'to'          => $message->getTo()[0]->full ?? 'Unknown',
                'subject'     => $message->getSubject() ?? 'No Subject',
                'date'        => $message->getDate()->toString(),
                'body'        => $message->hasHTMLBody() ? strip_tags($message->getHTMLBody()) : $message->getTextBody(),
                'uid'         => $message->getUid(),
                'attachments' => $message->getAttachments()->map(fn ($attachment) => [
                    'name' => $attachment->name,
                    'type' => $attachment->getMimeType(),
                    'is_image' => str_starts_with($attachment->getMimeType(), 'image/'), // ✅ Corrected
                    'size' => $attachment->size,
                ]),
            ]);
        } catch (\Throwable $e) {
            throw new InboxException("Failed to retrieve messages from folder: {$folder}" . $e->getMessage(), 0, $e);
        }
    }


    public function getMessage(string $folder, string $uid): ?MessageDTO
    {
        try {
            $mailFolder = $this->clientManager->getFolder($folder);
            $message = $mailFolder->query()->whereUid($uid)->get()->first();

            return $message ? MessageDTOFactory::createFromMessage($message) : null;
        } catch (\Throwable $e) {
            throw new InboxException("Failed to retrieve message UID: $uid from folder: $folder", 0, $e);
        }
    }

}
