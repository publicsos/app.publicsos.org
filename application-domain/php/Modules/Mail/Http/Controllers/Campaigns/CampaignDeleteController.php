<?php

declare(strict_types=1);

namespace Modules\Mail\Http\Controllers\Campaigns;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Mail\Facades\LaravelMail;
use Modules\Mail\Http\Controllers\Controller;
use Modules\Mail\Repositories\Campaigns\CampaignTenantRepositoryInterface;

class CampaignDeleteController extends Controller
{
    /** @var CampaignTenantRepositoryInterface */
    protected $campaigns;


    private int $workspaceID = 1;

    public function __construct(CampaignTenantRepositoryInterface $campaigns)
    {
        $this->campaigns = $campaigns;
    }

    /**
     * Show a confirmation view prior to deletion.
     *
     * @return RedirectResponse|View
     * @throws Exception
     */
    public function confirm(int $id)
    {
        $campaign = $this->campaigns->find($this->workspaceID, $id);

        if (! $campaign->draft) {
            return redirect()->route('backend.campaigns.index')
                ->withErrors(__('Unable to delete a campaign that is not in draft status'));
        }

        return view('mail::backend.campaigns.delete', compact('campaign'));
    }


    public function destroy(Request $request): RedirectResponse
    {
        $campaign = $this->campaigns->find($this->workspaceID, $request->get('id'));

        if (! $campaign->draft) {
            return redirect()->route('backend.campaigns.index')
                ->withErrors(__('Unable to delete a campaign that is not in draft status'));
        }

        $this->campaigns->destroy(LaravelMail::currentWorkspaceId(), $request->get('id'));

        return redirect()->route('backend.campaigns.index')
            ->with('success', __('The Campaign has been successfully deleted'));
    }
}
