<?php

declare(strict_types=1);

namespace Modules\Mail\Http\Controllers\Campaigns;

use AWS\CRT\Log;
use Exception;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\Client\Request as ClientRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Js;
use Modules\Mail\Facades\LaravelMail;
use Modules\Mail\Http\Controllers\Controller;
use Modules\Mail\Http\Requests\CampaignStoreRequest;
use Modules\Mail\Models\EmailService;
use Modules\Mail\Repositories\Campaigns\CampaignTenantRepositoryInterface;
use Modules\Mail\Repositories\EmailServiceTenantRepository;
use Modules\Mail\Repositories\Subscribers\SubscriberTenantRepositoryInterface;
use Modules\Mail\Repositories\TagTenantRepository;
use Modules\Mail\Repositories\TemplateTenantRepository;
use Modules\Mail\Services\Campaigns\CampaignStatisticsService;

use Modules\Mail\Services\Agents\CloudflareAgent;

class CampaignsController extends Controller
{
    protected CampaignTenantRepositoryInterface $campaigns;

    protected TemplateTenantRepository $templates;

    protected TagTenantRepository $tags;

    protected EmailServiceTenantRepository $emailServices;

    protected SubscriberTenantRepositoryInterface $subscribers;

    protected CampaignStatisticsService $campaignStatisticsService;

    private int $workspaceId = 1;

    private string $module_name = 'campaigns';

    private string $module_path = 'mail::backend';

    public function __construct(
        CampaignTenantRepositoryInterface $campaigns,
        TemplateTenantRepository $templates,
        TagTenantRepository $tags,
        EmailServiceTenantRepository $emailServices,
        SubscriberTenantRepositoryInterface $subscribers,
        CampaignStatisticsService $campaignStatisticsService
    ) {
        $this->campaigns = $campaigns;
        $this->templates = $templates;
        $this->tags = $tags;
        $this->emailServices = $emailServices;
        $this->subscribers = $subscribers;
        $this->campaignStatisticsService = $campaignStatisticsService;
    }

    public function index(): ViewContract
    {
        $params = ['draft' => true];
        $campaigns = $this->campaigns->paginate(1, 'created_atDesc', ['status'], 25, $params);

        return view("{$this->module_path}.{$this->module_name}.index", [
            'campaigns' => $campaigns,
            'campaignStats' => $this->campaignStatisticsService->getForPaginator($campaigns, 1),
        ]);
    }

    public function sent(): ViewContract
    {
        $params = ['sent' => true];

        $campaigns = $this->campaigns->paginate($this->workspaceId, 'created_atDesc', ['status'], 25, $params);

        return view('mail::backend.campaigns.index', [
            'campaigns' => $campaigns,
            'campaignStats' => $this->campaignStatisticsService->getForPaginator($campaigns, $this->workspaceId),
        ]);
    }


    public function create(): ViewContract
    {

        $templates = [null => '- None -'] + $this->templates->pluck(1);
        $emailServices = $this->emailServices->all(1, 'id', ['type'])
            ->map(static function (EmailService $emailService) {
                $emailService->formatted_name = "{$emailService->name} ({$emailService->type->name})";
                return $emailService;
            });

        return view('mail::backend.campaigns.create', compact('templates', 'emailServices'));
    }


    public function generate(): ViewContract
    {

        return throw new \RuntimeException("Check roadmap");

        $templates = [null => '- None -'] + $this->templates->pluck($this->workspaceId);
        $emailServices = $this->emailServices->all($this->workspaceId, 'id', ['type'])
            ->map(static function (EmailService $emailService) {
                $emailService->formatted_name = "{$emailService->name} ({$emailService->type->name})";
                return $emailService;
            });

        return view('mail::backend.campaigns.generate', compact('templates', 'emailServices'));
    }



    public function store(CampaignStoreRequest $request): RedirectResponse
    {
        $campaign = $this->campaigns->store($this->workspaceId, $this->handleCheckboxes($request->validated()));

        return redirect()->route('backend.campaigns.preview', $campaign->id);
    }


    public function show(int $id): ViewContract
    {

        $campaign = $this->campaigns->find($this->workspaceId, $id);


        return view('mail::backend.campaigns.show', compact('campaign'));
    }


    public function edit(int $id): ViewContract
    {

        $campaign = $this->campaigns->find($this->workspaceId, $id);

        $emailServices = $this->emailServices->all($this->workspaceId, 'id', ['type'])
            ->map(static function (EmailService $emailService) {
                $emailService->formatted_name = "{$emailService->name} ({$emailService->type->name})";
                return $emailService;
            });
        $templates = [null => '- None -'] + $this->templates->pluck($this->workspaceId);

        return view('mail::backend.campaigns.edit', compact('campaign', 'emailServices', 'templates'));
    }


    public function update(int $campaignId, CampaignStoreRequest $request): RedirectResponse
    {

        $campaign = $this->campaigns->update(
            $this->workspaceId,
            $campaignId,
            $this->handleCheckboxes($request->validated())
        );

        return redirect()->route('backend.campaigns.preview', $campaign->id);
    }


    public function preview(int $id)
    {

        $campaign = $this->campaigns->find($this->workspaceId, $id);

        $subscriberCount = $this->subscribers->countActive($this->workspaceId);

        if (! $campaign->draft) {
            return redirect()->route('backend.campaigns.status', $id);
        }

        $tags = $this->tags->all($this->workspaceId, 'name');

        return view('mail::backend.campaigns.preview', compact('campaign', 'tags', 'subscriberCount'));
    }


    public function status(int $id)
    {

        $campaign = $this->campaigns->find($this->workspaceId, $id, ['status']);

        if ($campaign->sent) {
            return redirect()->route('backend.campaigns.reports.index', $id);
        }

        return view('mail::backend.campaigns.status', [
            'campaign' => $campaign,
            'campaignStats' => $this->campaignStatisticsService->getForCampaign($campaign, $this->workspaceId),
        ]);
    }

    private function handleCheckboxes(array $input): array
    {
        return $input;
    }


    public function rewrite():mixed
    {
        $params = request()->only('content');

        $agent = new CloudflareAgent();

        $response = $agent->run($params['content']);

        return response()->json([
            'content' => $response->result
        ]);
    }
}
