<?php

declare(strict_types=1);

namespace LaravelCompany\Mail\Http\Controllers\Webview;

use Exception;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Support\Facades\Log;
use LaravelCompany\Mail\Http\Controllers\Controller;
use LaravelCompany\Mail\Models\Message;
use LaravelCompany\Mail\Services\Content\MergeContentService;
use RuntimeException;
use LaravelCompany\Mail\Services\Tracking\TrackingService;
use AlexWestergaard\PhpGa4\Event\SelectContent;
use LaravelCompany\Mail\Models\MessageUrl;
use LaravelCompany\Mail\Services\Webhooks\EmailWebhookService;
class WebviewController extends Controller
{
    /** @var MergeContentService */
    private $merger;

    /** @var EmailWebhookService */
    private EmailWebhookService $emailWebhookService;

    /** @var TrackingService */
    private $trackingService;

    public function __construct(MergeContentService $merger, EmailWebhookService $emailWebhookService, TrackingService $trackingService)
    {
        $this->merger = $merger;

        $this->emailWebhookService = $emailWebhookService;

        $this->trackingService = $trackingService;
    }

    /**
     * @throws Exception
     */
    public function show(string $messageHash): ViewContract
    {
        /** @var Message $message */
        $message = Message::with('subscriber')->where('hash', $messageHash)->first();

        if (!$message) {
            throw new RuntimeException('Message not found');
        }
        $content = $this->merger->handle($message);

        return view('laravel-mail::webview.show', compact('content'));
    }

    /**
     * This route sends an event to the the
     */
    public function recordInvisiblePixel(string $messageHash): void
    {
        Log::info('TODO: Implement Invisible tracking recorded for message hash '. $messageHash);

       $analyticsService = new TrackingService();

        $selectItemEvent = new SelectContent();

        $selectItemEvent->setItemId($analyticsService->analytics->client_id);

        $selectItemEvent->setContentType("email");
        $selectItemEvent->setItemId($messageHash);

        $response = $analyticsService->sendEvent($selectItemEvent);

        Log::info("Google response for message $messageHash whas {json_encode($response)}");

    }


    public function recordClickAndRedirect(string $messageHash, string $url)
    {

        $urlDecoded = base64_decode($url);

        $message = Message::where('hash', $messageHash)->firstOrFail();

        $this->emailWebhookService->handleClick($message->message_id, now(), $urlDecoded);

        return redirect($urlDecoded);
    }

}
