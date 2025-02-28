<?php

declare(strict_types=1);

namespace Modules\Mail\Http\Controllers\Campaigns;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Modules\Mail\Facades\LaravelMail;
use Modules\Mail\Http\Controllers\Controller;
use Modules\Mail\Models\Campaign;
use Modules\Mail\Presenters\CampaignReportPresenter;
use Modules\Mail\Repositories\Campaigns\CampaignTenantRepositoryInterface;
use Modules\Mail\Repositories\Messages\MessageTenantRepositoryInterface;

class CampaignReportsController extends Controller
{
    protected CampaignTenantRepositoryInterface $campaignRepo;

    protected MessageTenantRepositoryInterface $messageRepo;


    private int $workspaceID = 1;

    public function __construct(
        CampaignTenantRepositoryInterface $campaignRepository,
        MessageTenantRepositoryInterface $messageRepo
    ) {
        $this->campaignRepo = $campaignRepository;
        $this->messageRepo = $messageRepo;
    }


    public function index(int $id, Request $request)
    {
        $campaign = $this->campaignRepo->find($this->workspaceID, $id);

        if ($campaign->draft) {
            return redirect()->route('backend.campaigns.edit', $id);
        }

        if ($campaign->queued || $campaign->sending) {
            return redirect()->route('backend.campaigns.status', $id);
        }

        $presenter = new CampaignReportPresenter(
            $campaign,
            $this->workspaceID,
            (int) $request->get('interval', 148
        ));

        $presenterData = $presenter->generate();

        $data = [
            'campaign' => $campaign,
            'campaignUrls' => $presenterData['campaignUrls'],
            'campaignStats' => $presenterData['campaignStats'],
            'chartLabels' => json_encode(Arr::get($presenterData['chartData'], 'labels', [])),
            'chartData' => json_encode(Arr::get($presenterData['chartData'], 'data', [])),
        ];

        return view('mail::backend.campaigns.reports.index', $data);
    }

    public function recipients(int $id)
    {
        $campaign = $this->campaignRepo->find($this->workspaceID, $id);

        if ($campaign->draft) {
            return redirect()->route('backend.campaigns.edit', $id);
        }

        if ($campaign->queued || $campaign->sending) {
            return redirect()->route('backend.campaigns.status', $id);
        }

        $messages = $this->messageRepo->recipients($this->workspaceID, Campaign::class, $id);

        return view('mail::backend.campaigns.reports.recipients', compact('campaign', 'messages'));
    }

    public function opens(int $id)
    {
        $campaign = $this->campaignRepo->find($this->workspaceID, $id);

        $averageTimeToOpen = $this->campaignRepo->getAverageTimeToOpen($campaign);

        if ($campaign->draft) {
            return redirect()->route('backend.campaigns.edit', $id);
        }

        if ($campaign->queued || $campaign->sending) {
            return redirect()->route('backend.campaigns.status', $id);
        }

        $messages = $this->messageRepo->opens($this->workspaceID, Campaign::class, $id);

        return view('mail::backend.campaigns.reports.opens', compact('campaign', 'messages', 'averageTimeToOpen'));
    }


    public function clicks(int $id)
    {
        $campaign = $this->campaignRepo->find($this->workspaceID, $id);

        $averageTimeToClick = $this->campaignRepo->getAverageTimeToClick($campaign);

        if ($campaign->draft) {
            return redirect()->route('backend.campaigns.edit', $id);
        }

        if ($campaign->queued || $campaign->sending) {
            return redirect()->route('backend.campaigns.status', $id);
        }

        $messages = $this->messageRepo->clicks($this->workspaceID, Campaign::class, $id);

        return view('mail::backend.campaigns.reports.clicks', compact('campaign', 'messages', 'averageTimeToClick'));
    }


    public function bounces(int $id)
    {
        $campaign = $this->campaignRepo->find($this->workspaceID, $id);

        if ($campaign->draft) {
            return redirect()->route('backend.campaigns.edit', $id);
        }

        if ($campaign->queued || $campaign->sending) {
            return redirect()->route('backend.campaigns.status', $id);
        }

        $messages = $this->messageRepo->bounces($this->workspaceID, Campaign::class, $id);

        return view('mail::backend.campaigns.reports.bounces', compact('campaign', 'messages'));
    }

    public function unsubscribes(int $id)
    {
        $campaign = $this->campaignRepo->find($this->workspaceID, $id);

        if ($campaign->draft) {
            return redirect()->route('backend.campaigns.edit', $id);
        }

        if ($campaign->queued || $campaign->sending) {
            return redirect()->route('backend.campaigns.status', $id);
        }

        $messages = $this->messageRepo->unsubscribes($this->workspaceID, Campaign::class, $id);

        return view('mail::backend.campaigns.reports.unsubscribes', compact('campaign', 'messages'));
    }
}
