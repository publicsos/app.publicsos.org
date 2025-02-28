<?php

declare(strict_types=1);

namespace Modules\Mail\Services\Messages;

use Exception;
use Illuminate\Support\Facades\Log;
use Modules\Mail\Models\Campaign;
use Modules\Mail\Models\CampaignStatus;
use Modules\Mail\Models\EmailService;
use Modules\Mail\Models\Message;
use Modules\Mail\Services\Content\MergeContentService;
use Modules\Mail\Services\Content\MergeSubjectService;

class DispatchMessage
{
    protected MergeContentService $mergeContentService;
    protected MergeSubjectService $mergeSubjectService;
    protected ResolveEmailService $resolveEmailService;
    protected RelayMessage $relayMessage;
    protected MarkAsSent $markAsSent;

    public function __construct(
        MergeContentService $mergeContentService,
        MergeSubjectService $mergeSubjectService,
        ResolveEmailService $resolveEmailService,
        RelayMessage $relayMessage,
        MarkAsSent $markAsSent
    ) {
        $this->mergeContentService = $mergeContentService;
        $this->mergeSubjectService = $mergeSubjectService;
        $this->resolveEmailService = $resolveEmailService;
        $this->relayMessage = $relayMessage;
        $this->markAsSent = $markAsSent;
    }


    public function handle(Message $message): ?string
    {
        if (! $this->isValidMessage($message)) {
            Log::info('Message skipped as it is not valid.', ['message_id' => $message->id]);
            return null;
        }

        $message = $this->mergeSubject($message);
        $mergedContent = $this->getMergedContent($message);
        $emailService = $this->getEmailService($message);
        $trackingOptions = MessageTrackingOptions::fromMessage($message);

        $messageId = $this->dispatch($message, $emailService, $trackingOptions, $mergedContent);

        Log::error('Message sent -> Message id received  -> ', ['message_id' => $messageId ?? ""]);

        return $messageId;

    }


    protected function mergeSubject(Message $message): Message
    {
        $message->subject = $this->mergeSubjectService->handle($message);
        $message->save();

        return $message;
    }


    protected function getMergedContent(Message $message): string
    {
        return $this->mergeContentService->handle($message);
    }


    protected function dispatch(Message $message, EmailService $emailService, MessageTrackingOptions $trackingOptions, string $mergedContent): ?string
    {
        $messageOptions = (new MessageOptions())
            ->setTo($message->recipient_email)
            ->setFromEmail($message->from_email)
            ->setFromName($message->from_name)
            ->setSubject($message->subject)
            ->setTrackingOptions($trackingOptions);

        $messageId = $this->relayMessage->handle($mergedContent, $messageOptions, $emailService);

        //todo this is possible to break the webhooks
        $this->markAsSent->handle($message, $messageId);

        return $messageId;
    }

    protected function getEmailService(Message $message): EmailService
    {
        return $this->resolveEmailService->handle($message);
    }


    protected function isValidMessage(Message $message): bool
    {
        if ($message->sent_at) {
            return false;
        }

        if (! $message->isCampaign()) {
            return true;
        }

        $campaign = Campaign::find($message->source_id);

        return $campaign && $campaign->status_id !== CampaignStatus::STATUS_CANCELLED;
    }
}
