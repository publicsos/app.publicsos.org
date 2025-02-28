<?php

declare(strict_types=1);

namespace Modules\Mail\Services\Content;

use Exception;
use Illuminate\Support\Facades\Log;
use Modules\Mail\Models\Message;
use Modules\Mail\Models\MessageUrl;
use Modules\Mail\Repositories\Campaigns\CampaignTenantRepositoryInterface;
use Modules\Mail\Repositories\AutomationScheduleRepository;
use Modules\Mail\Traits\NormalizeTags;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;
use Symfony\Component\DomCrawler\Crawler;

class ContentMergeException extends Exception {}

class MergeContentService
{
    use NormalizeTags;

    private CampaignTenantRepositoryInterface $campaignRepo;
    private CssToInlineStyles $cssProcessor;

    public function __construct(
        CampaignTenantRepositoryInterface $campaignRepo,
        CssToInlineStyles $cssProcessor
    ) {
        $this->campaignRepo = $campaignRepo;
        $this->cssProcessor = $cssProcessor;
    }

    /**
     * Handle the message content processing.
     *
     * @throws ContentMergeException
     */
    public function handle(Message $message): string
    {
        try {
            $content = $this->resolveContent($message);
            $content = $this->inlineStyles($content);
            //$content = $this->addInvisiblePixel($content, $message);
            //$content = $this->replaceLinksWithTracking($content, $message);

            return $content;

        } catch (ContentMergeException $e) {
            Log::error("Content merge failed for message id={$message->id}. Error: {$e->getMessage()}");
            throw $e;
        }
    }

    /**
     * Resolve the content based on the message type.
     *
     * @throws ContentMergeException
     */
    protected function resolveContent(Message $message): string
    {
        if ($message->isCampaign()) {
            $mergedContent = $this->mergeCampaignContent($message);
        } elseif ($message->isAutomation()) {
            $mergedContent = $this->mergeAutomationContent($message);
        } else {
            throw new ContentMergeException('Invalid message source type for message id=' . $message->id);
        }

        return $this->mergeTags($mergedContent, $message);
    }

    /**
     * Merge campaign content.
     *
     * @throws ContentMergeException
     */
    private function mergeCampaignContent(Message $message): string
    {
        $campaign = $this->campaignRepo->find($message->workspace_id, $message->source_id, ['template']);

        if (!$campaign) {
            throw new ContentMergeException('Unable to resolve campaign for message id=' . $message->id);
        }

        return $campaign->template
            ? $this->mergeContent($campaign->content, $campaign->template->content)
            : $campaign->content;
    }

    /**
     * Merge automation content.
     *
     * @throws ContentMergeException
     */
    private function mergeAutomationContent(Message $message): string
    {
        //todo Inspect this
        $schedule = app(AutomationScheduleRepository::class)->find($message->source_id, ['automation_step']);

        if (!$schedule) {
            throw new ContentMergeException('Unable to resolve automation schedule for message id=' . $message->id);
        }

        if (!$content = $schedule->automation_step->content) {
            throw new ContentMergeException('Unable to resolve content for automation step id=' . $schedule->automation_step_id);
        }

        if (!$template = $schedule->automation_step->template) {
            throw new ContentMergeException('Unable to resolve template for automation step id=' . $schedule->automation_step_id);
        }

        return $this->mergeContent($content, $template->content);
    }

    private function mergeContent(?string $customContent, string $templateContent): string
    {
        return str_ireplace(['{{content}}', '{{ content }}'], $customContent ?: '', $templateContent);
    }

    private function mergeTags(string $content, Message $message): string
    {
        $content = $this->compileTags($content);
        $content = $this->mergeSubscriberTags($content, $message);
        $content = $this->mergeUnsubscribeLink($content, $message);
        return $this->mergeWebviewLink($content, $message);
    }

    private function compileTags(string $content): string
    {
        $tags = ['email', 'first_name', 'last_name', 'meta', 'unsubscribe_url', 'webview_url', 'tracking_url'];

        foreach ($tags as $tag) {
            $content = $this->normalizeTags($content, $tag);
        }

        return $content;
    }

    private function mergeSubscriberTags(string $content, Message $message): string
    {
        $tags = [
            'email' => $message->recipient_email,
            'first_name' => optional($message->subscriber)->first_name ?? '',
            'last_name' => optional($message->subscriber)->last_name ?? ''
        ];

        foreach ($tags as $key => $replace) {
            $content = str_ireplace('{{' . $key . '}}', $replace, $content);
        }

        return $content;
    }

    private function mergeUnsubscribeLink(string $content, Message $message): string
    {
        $unsubscribeLink = $this->generateUnsubscribeLink($message);

        return str_ireplace(['{{ unsubscribe_url }}', '{{unsubscribe_url}}'], $unsubscribeLink, $content);
    }

    private function generateUnsubscribeLink(Message $message): string
    {
        return route('unsubscribe', $message->hash);
    }

    private function mergeWebviewLink(string $content, Message $message): string
    {
        $webviewLink = $this->generateWebviewLink($message);

        return str_ireplace('{{webview_url}}', $webviewLink, $content);
    }

    private function generateWebviewLink(Message $message): string
    {
        return route('webview.show', $message->hash);
    }

    private function inlineStyles(string $content): string
    {
        return $this->cssProcessor->convert($content);
    }

    private function addInvisiblePixel(string $content, Message $message): string
    {
        $trackingPixelUrl = route('webview.record-invisible-pixel', $message->hash);
        $trackingPixel = "<img src='{$trackingPixelUrl}' width='1' height='1' style='display:none;'>";
        return $content . $trackingPixel;
    }


    private function replaceLinksWithTracking(string $content, $message)
    {
        $crawler = new Crawler($content);

        // Extract all <a> tags
        $links = $crawler->filter('a');

        if ($links->count() === 0) {
            // If no links are found, return the content as-is
            return $content;
        }

        // Collection to store processed links
        $trackedLinks = collect([]);

        // Array of URLs to exclude from tracking
        $excludedKeywords = ['unsubscribe_url', 'webview'];

        // Iterate through each link and modify its href attribute
        $links->each(function (Crawler $node) use (&$content, $message, $trackedLinks, $excludedKeywords) {
            $href = $node->attr('href');

            if (!$href) {
                // Skip links without href attributes
                return;
            }

            // Check if the URL contains excluded keywords
            foreach ($excludedKeywords as $keyword) {
                if (stripos($href, $keyword) !== false) {
                    // Add to the collection of excluded links and skip processing
                    $trackedLinks->push(['excluded' => $href]);
                    return;
                }
            }

            // Generate the tracking URL
            $trackingUrl = $this->generateTrackingUrl($message->hash, $href);

            // Replace the old link with the tracked link in the content
            $originalLink = $node->outerHtml();
            $updatedLink = str_replace($href, htmlspecialchars($trackingUrl), $originalLink);
            $content = str_replace($originalLink, $updatedLink, $content);

            // Add to the collection of processed links
            $trackedLinks->push(['original' => $href, 'tracked' => $trackingUrl]);
        });

        // Debug the collection of links
        // dd($trackedLinks);

        return $content;
    }

    /**
     * Generate a tracking URL by appending tracking parameters and storing the URL in the database.
     */
    private function generateTrackingUrl(string $messageHash, string $url): string
    {
        // Store or retrieve the URL tracking record
        $messageUrl = MessageUrl::firstOrCreate(
            ['hash' => hash('sha256', $url)],
            [
                'source_type' => 'message',
                'source_id'   => $messageHash,
                'url'         => $url,
            ]
        );

        // Generate the tracking link with a hashed reference
        return route("webview.record-click-link", [
            'messageHash' => $messageHash,
            'urlHash'     => urlencode($messageUrl->hash),
        ]);
    }
}
