<?php

namespace LaravelCompany\Mail\Http\Controllers\Api;

use LaravelCompany\Mail\Facades\LaravelMail;
use LaravelCompany\Mail\Http\Controllers\Controller;
use LaravelCompany\Mail\Http\Requests\Api\CampaignDispatchRequest;
use LaravelCompany\Mail\Http\Resources\Campaign as CampaignResource;
use LaravelCompany\Mail\Interfaces\QuotaServiceInterface;
use LaravelCompany\Mail\Models\CampaignStatus;
use LaravelCompany\Mail\Repositories\Campaigns\CampaignTenantRepositoryInterface;
use Illuminate\Support\Carbon;

class CampaignDispatchController extends Controller
{
    /**
     * @var CampaignTenantRepositoryInterface
     */
    protected $campaigns;

    /**
     * @var QuotaServiceInterface
     */
    protected $quotaService;

    public function __construct(
        CampaignTenantRepositoryInterface $campaigns,
        QuotaServiceInterface $quotaService
    ) {
        $this->campaigns = $campaigns;
        $this->quotaService = $quotaService;
    }

    /**
     * @throws \Exception
     */
    public function send(CampaignDispatchRequest $request, $campaignId)
    {


        $campaign = $request->getCampaign(['email_service', 'messages']);

        $workspaceId = LaravelMail::currentWorkspaceId();



        if ($campaign->status_id !== CampaignStatus::STATUS_DRAFT) {
            return redirect()->route('laravel-mail.campaigns.status', $id);
        }

        if (! $campaign->email_service_id) {
            return redirect()->route('laravel-mail.campaigns.edit', $id)
                ->withErrors(__('Please select an Email Service'));
        }

        $campaign->update([
            'send_to_all' => $request->get('recipients') === 'send_to_all',
        ]);

        $campaign->tags()->sync($request->get('tags'));

        if ($this->quotaService->exceedsQuota($campaign->email_service, $campaign->unsent_count)) {
            return response([
                'message' => __('The number of subscribers for this campaign exceeds your current quota')
            ], 422);
        }

        $scheduledAt = $request->get('schedule') === 'scheduled' ? Carbon::parse($request->get('scheduled_at')) : now();

        $campaign->update([
            'scheduled_at' => $scheduledAt,
            'status_id' => CampaignStatus::STATUS_QUEUED,
            'save_as_draft' => $request->get('behaviour') === 'draft',
        ]);

        return new CampaignResource($campaign);


    }
}
