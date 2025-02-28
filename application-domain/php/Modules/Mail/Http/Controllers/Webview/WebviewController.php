<?php

declare(strict_types=1);

namespace Modules\Mail\Http\Controllers\Webview;

use Exception;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Support\Facades\Log;
use Modules\Mail\Http\Controllers\Controller;
use Modules\Mail\Models\Message;
use Modules\Mail\Services\Content\MergeContentService;
use RuntimeException;
use Modules\Mail\Services\Tracking\TrackingService;
use AlexWestergaard\PhpGa4\Event\SelectContent;
use Modules\Mail\Services\Webhooks\EmailWebhookService;
class WebviewController extends Controller
{
    private MergeContentService $merger;


    private EmailWebhookService $emailWebhookService;

    private TrackingService $trackingService;

    public function __construct(MergeContentService $merger, EmailWebhookService $emailWebhookService, TrackingService $trackingService)
    {
        $this->merger = $merger;

        $this->emailWebhookService = $emailWebhookService;

        $this->trackingService = $trackingService;
    }

    public function show(string $messageHash): ViewContract
    {
        $message = Message::with('subscriber')->where('hash', $messageHash)->first();

        if (!$message) {
            throw new RuntimeException('Message not found');
        }
        $content = $this->merger->handle($message);

        return view('mail::backend.webview.show', compact('content'));
    }


    public function recordInvisiblePixel(string $messageHash): void
    {
        Log::info('TODO: Implement Invisible tracking recorded for message hash '. $messageHash);

       $analyticsService = new TrackingService();

        $selectItemEvent = new SelectContent();

        $selectItemEvent->setItemId($this->trackingService->analytics->client_id);

        $selectItemEvent->setContentType("email");
        $selectItemEvent->setItemId($messageHash);

        $response = $analyticsService->sendEvent($selectItemEvent);

        Log::info("Google response for message $messageHash whas {json_encode($response)}");

    }


    public function recordClickAndRedirect(string $messageHash, string $urlHash)
    {
        $url = base64_decode($urlHash);

        $this->emailWebhookService->handleClick($messageHash, now(), $url);

        return redirect($url);
    }

}
