<?php

declare(strict_types=1);

namespace Modules\Mail\Http\Controllers\Campaigns;

use Exception;
use Illuminate\Http\RedirectResponse;
use Modules\Mail\Facades\LaravelMail;

use Modules\Mail\Http\Controllers\Controller;
use Modules\Mail\Http\Requests\CampaignTestRequest;
use Modules\Mail\Services\Messages\DispatchTestMessage;

class CampaignTestController extends Controller
{
    protected DispatchTestMessage $dispatchTestMessage;

    private int $workspaceID = 1;

    public function __construct(DispatchTestMessage $dispatchTestMessage)
    {
        $this->dispatchTestMessage = $dispatchTestMessage;
    }

    public function handle(CampaignTestRequest $request, int $campaignId): RedirectResponse
    {
        $messageId = $this->dispatchTestMessage->handle($this->workspaceID, $campaignId, $request->get('recipient_email'));

        if (! $messageId) {
            return redirect()->route('backend.campaigns.preview', $campaignId)
                ->withInput()
                ->with(['error', __('Failed to dispatch test email.')]);
        }

        return redirect()->route('backend.campaigns.preview', $campaignId)
            ->withInput()
            ->with(['success' => __('The test email has been dispatched.')]);
    }
}
