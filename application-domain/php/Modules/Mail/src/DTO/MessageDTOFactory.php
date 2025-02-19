<?php
declare(strict_types=1);

namespace LaravelCompany\Mail\DTO;

use Webklex\PHPIMAP\Message;

class MessageDTOFactory
{
    public static function createFromMessage(Message $message): MessageDTO
    {
        return new MessageDTO(
            subject: $message->getSubject()->toString() ?? '(No Subject)',
            from: $message->getFrom()[0]->mail ?? 'Unknown',
            to: $message->getTo()[0]->mail ?? 'Unknown',
            date: $message->getDate()->toString('Y-m-d H:i:s'),
            body: $message->getTextBody() ?? '',
            attachments: self::parseAttachments($message)
        );
    }

    private static function parseAttachments(Message $message): array
    {
        return collect($message->getAttachments())->map(fn ($attachment) => [
            'name' => $attachment->name,
            'type' => $attachment->getMimeType(),
            'img_src' => $attachment->isImage() ? $attachment->getDataUri() : null,
        ])->toArray();
    }
}
