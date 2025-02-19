<?php

declare(strict_types=1);

namespace LaravelCompany\Mail\Http\Controllers\EmailServices;

use Exception;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use LaravelCompany\Mail\Facades\LaravelMail;
use LaravelCompany\Mail\Http\Controllers\Controller;
use LaravelCompany\Mail\Http\Requests\EmailServiceRequest;
use LaravelCompany\Mail\Repositories\EmailServiceTenantRepository;
use Illuminate\Http\Request;
use LaravelCompany\Mail\Jobs\VerifySmtpJob;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Redis;


class EmailServicesController extends Controller
{
    /** @var EmailServiceTenantRepository */
    private $emailServices;


    private int $workspaceId;

    public function __construct(EmailServiceTenantRepository $emailServices)
    {
        $this->emailServices = $emailServices;
        $this->workspaceId = LaravelMail::currentWorkspaceId();
    }

    /**
     * @throws Exception
     */
    public function index(): View
    {
        $this->workspaceId = LaravelMail::currentWorkspaceId();
        $emailServices = $this->emailServices->all($this->workspaceId);

        return view('laravel-mail::email_services.index', compact('emailServices'));
    }

    public function create(): View
    {
        $emailServiceTypes = $this->emailServices->getEmailServiceTypes()->pluck('name', 'id');

        return view('laravel-mail::email_services.create', compact('emailServiceTypes'));
    }

    /**
     * @throws Exception
     */
    public function store(EmailServiceRequest $request): RedirectResponse
    {
        $this->workspaceId = LaravelMail::currentWorkspaceId();

        $emailServiceType = $this->emailServices->findType($request->type_id);

        $settings = $request->get('settings', []);

        $this->emailServices->store($this->workspaceId, [
            'name' => $request->name,
            'type_id' => $emailServiceType->id,
            'settings' => $settings,
        ]);

        return redirect()->route('laravel-mail.email_services.index');
    }

    /**
     * @throws Exception
     */
    public function edit(int $emailServiceId)
    {
        $this->workspaceId = LaravelMail::currentWorkspaceId();

        $emailServiceTypes = $this->emailServices->getEmailServiceTypes()->pluck('name', 'id');
        $emailService = $this->emailServices->find($this->workspaceId, $emailServiceId);
        $emailServiceType = $this->emailServices->findType($emailService->type_id);

        return view('laravel-mail::email_services.edit', compact('emailServiceTypes', 'emailService', 'emailServiceType'));
    }

    /**
     * @throws Exception
     */
    public function update(EmailServiceRequest $request, int $emailServiceId): RedirectResponse
    {
        $this->workspaceId = LaravelMail::currentWorkspaceId();

        $emailService = $this->emailServices->find($this->workspaceId, $emailServiceId, ['type']);

        $settings = $request->get('settings');

        $emailService->name = $request->name;
        $emailService->settings = $settings;
        $emailService->save();

        return redirect()->route('laravel-mail.email_services.index');
    }

    /**
     * @throws Exception
     */
    public function delete(int $emailServiceId): RedirectResponse
    {

        $this->workspaceId = LaravelMail::currentWorkspaceId();

        $emailService = $this->emailServices->find($this->workspaceId, $emailServiceId, ['campaigns']);

        if ($emailService->in_use) {
            return redirect()->back()->withErrors(__("You cannot delete an email service that is currently used by a campaign or automation."));
        }

        $this->emailServices->destroy($this->workspaceId, $emailServiceId);

        return redirect()->route('laravel-mail.email_services.index');
    }

    /**
     * @throws BindingResolutionException
     */
    public function emailServicesTypeAjax(int $emailServiceTypeId): JsonResponse
    {
        $emailServiceType = $this->emailServices->findType($emailServiceTypeId);

        return response()->json([
            'view' => view()
                ->make('laravel-mail::email_services.options.' . strtolower($emailServiceType->name))
                ->render()
        ]);
    }

    public function verify(): View
    {
        $workspaceId = LaravelMail::currentWorkspaceId();
        $redisKey = "smtp_verification_jobs:workspace:{$workspaceId}";
        $jobsRedis = Redis::hgetall($redisKey) ?? [];

        $jobs = [];
        foreach ($jobsRedis as $jobId => $jobData) {
            $jobs[] = json_decode($jobData, true);
        }
        return view('laravel-mail::email_services.verify' , compact('jobs'));
    }
    public function postVerify(Request $request): RedirectResponse
    {
        $workspaceId = LaravelMail::currentWorkspaceId();
        $file = $request->file('file');

        // Validate the file
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        // Read the CSV file using Laravel's built-in CSV reader
        $csvData = array_map('str_getcsv', file($file->getRealPath()));

        $jobCount = 0;

        foreach ($csvData as $row) {
            // Skip empty rows
            if (empty($row[0])) {
                continue;
            }

            $array = explode('|', $row[0]);

            // Skip rows with missing data
            if (count($array) < 4) {
                continue;
            }

            [$server, $port, $email, $password] = $array;

            // Generate a unique job ID
            $jobId = Str::uuid();

            // Dispatch the job to the queue
            VerifySmtpJob::dispatch($server, $port, $email, $password, $workspaceId, $jobId)
                ->onQueue('smtp-validation');

            $jobCount++;
        }

        // Flash a success message with the number of dispatched jobs
        session()->flash('success', __('SMTP verification jobs dispatched. Total jobs sent: :count', ['count' => $jobCount]));

        return redirect()->route('laravel-mail.email_services.index');
    }
}
