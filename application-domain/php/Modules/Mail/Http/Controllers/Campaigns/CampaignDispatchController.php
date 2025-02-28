<?php

declare(strict_types=1);

namespace Modules\Mail\Http\Controllers\Campaigns;

use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Modules\Mail\Facades\LaravelMail;
use Modules\Mail\Facades\Sendportal;
use Modules\Mail\Http\Controllers\Controller;
use Modules\Mail\Http\Requests\CampaignDispatchRequest;
use Modules\Mail\Interfaces\QuotaServiceInterface;
use Modules\Mail\Models\CampaignStatus;
use Modules\Mail\Repositories\Campaigns\CampaignTenantRepositoryInterface;

class CampaignDispatchController extends Controller
{

    protected $campaigns;

    protected $quotaService;

    private int $workspaceID = 1;

    public function __construct(
        CampaignTenantRepositoryInterface $campaigns,
        QuotaServiceInterface $quotaService
    ) {
        $this->campaigns = $campaigns;
        $this->quotaService = $quotaService;
    }


    public function send(CampaignDispatchRequest $request, int $id): RedirectResponse
    {

        $campaign = $this->campaigns->find($this->workspaceID, $id, ['email_service', 'messages']);

        if ($campaign->status_id !== CampaignStatus::STATUS_DRAFT) {
            return redirect()->route('backend.campaigns.status', $id);
        }

        if (! $campaign->email_service_id) {
            return redirect()->route('backend.campaigns.edit', $id)
                ->withErrors(__('Please select an Email Service'));
        }

       $campaign->update([
           'send_to_all' => $request->get('recipients') === 'send_to_all',
       ]);

       $campaign->tags()->sync($request->get('tags'));

        if ($this->quotaService->exceedsQuota($campaign->email_service, $campaign->unsent_count)) {
            return redirect()->route('backend.campaigns.edit', $id)
                ->withErrors(__('The number of subscribers for this campaign exceeds your SES quota'));
        }

        $scheduledAt = $request->get('schedule') === 'scheduled' ? Carbon::parse($request->get('scheduled_at')) : now();

        $campaign->update([
            'scheduled_at' => $scheduledAt,
            'status_id' => CampaignStatus::STATUS_QUEUED,
            'save_as_draft' => $request->get('behaviour') === 'draft',
        ]);

        return redirect()->route('backend.campaigns.status', $id);
    }
}
