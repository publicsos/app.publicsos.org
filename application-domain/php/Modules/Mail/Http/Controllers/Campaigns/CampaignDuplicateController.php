<?php

declare(strict_types=1);

namespace Modules\Mail\Http\Controllers\Campaigns;

use Exception;
use Illuminate\Http\RedirectResponse;
use Modules\Mail\Facades\LaravelMail;
use Modules\Mail\Http\Controllers\Controller;
use Modules\Mail\Models\CampaignStatus;
use Modules\Mail\Repositories\Campaigns\CampaignTenantRepositoryInterface;

class CampaignDuplicateController extends Controller
{
    protected CampaignTenantRepositoryInterface $campaigns;

    private int $workspaceID = 1;

    public function __construct(CampaignTenantRepositoryInterface $campaigns)
    {
        $this->campaigns = $campaigns;
    }

    public function duplicate(int $campaignId): RedirectResponse
    {
        $campaign = $this->campaigns->find($this->workspaceID, $campaignId);

        return redirect()->route('backend.campaigns.create')->withInput([
            'name' => $campaign->name . ' - Duplicate',
            'status_id' => CampaignStatus::STATUS_DRAFT,
            'template_id' => $campaign->template_id,
            'email_service_id' => $campaign->email_service_id,
            'subject' => $campaign->subject,
            'content' => $campaign->content,
            'from_name' => $campaign->from_name,
            'from_email' => $campaign->from_email,
        ]);
    }
}
