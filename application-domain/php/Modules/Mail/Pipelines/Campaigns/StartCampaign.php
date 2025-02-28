<?php
declare(strict_types=1);
namespace Modules\Mail\Pipelines\Campaigns;

use Modules\Mail\Models\Campaign;
use Modules\Mail\Models\CampaignStatus;


class StartCampaign
{
    /**
     * Mark the campaign as started in the database
     *
     * @param Campaign $campaign
     * @param $next
     * @return Campaign
     */
    public function handle(Campaign $campaign, $next)
    {

        $this->markCampaignAsSending($campaign);

        return $next($campaign);
    }

    /**
     * Execute the database request
     *
     * @param Campaign $campaign
     * @return Campaign|null
     */
    protected function markCampaignAsSending(Campaign $campaign): ?Campaign
    {
        return tap($campaign)->update([
            'status_id' => CampaignStatus::STATUS_SENDING,
        ]);
    }
}
