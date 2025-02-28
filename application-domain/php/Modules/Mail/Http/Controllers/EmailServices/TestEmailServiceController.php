<?php
declare(strict_types=1);
namespace Modules\Mail\Http\Controllers\EmailServices;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Mail\Facades\LaravelMail;
use Modules\Mail\Facades\Sendportal;
use Modules\Mail\Http\Controllers\Controller;
use Modules\Mail\Http\Requests\EmailServiceTestRequest;
use Modules\Mail\Repositories\EmailServiceTenantRepository;
use Modules\Mail\Services\Messages\DispatchTestMessage;
use Modules\Mail\Services\Messages\MessageOptions;

class TestEmailServiceController extends Controller
{

    private EmailServiceTenantRepository $emailServices;

    private int $workspaceId = 1;

    public function __construct(EmailServiceTenantRepository $emailServices)
    {
        $this->emailServices = $emailServices;

    }

    public function create(int $emailServiceId): View
    {
        $emailService = $this->emailServices->find($this->workspaceId, $emailServiceId);

        return view('mail::backend.email_services.test', compact('emailService'));
    }


    public function store(int $emailServiceId, EmailServiceTestRequest $request, DispatchTestMessage $dispatchTestMessage): RedirectResponse
    {

        $emailService = $this->emailServices->find($this->workspaceId, $emailServiceId);

        $options = new MessageOptions();
        $options->setFromEmail($request->input('from'));
        $options->setSubject($request->input('subject'));
        $options->setTo($request->input('to'));
        $options->setBody($request->input('body'));


        try {

            $messageId = $dispatchTestMessage->testService($this->workspaceId, $emailService, $options);

            if (! $messageId) {
                return redirect()->back()->with(['error', __('Failed to dispatch test email.')]);
            }

            return redirect()->route('backend.email-services.index')->with(['success' => __('The test email has been dispatched.')]);

        } catch (Exception $e) {

            return redirect()->back()->with('error', 'Response: ' . $e->getMessage());
        }
    }
}
