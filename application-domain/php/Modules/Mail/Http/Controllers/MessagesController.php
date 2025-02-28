<?php

declare(strict_types=1);

namespace Modules\Mail\Http\Controllers;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Mail\Facades\LaravelMail;
use Modules\Mail\Models\Message;
use Modules\Mail\Repositories\Messages\MessageTenantRepositoryInterface;
use Modules\Mail\Services\Content\MergeContentService;
use Modules\Mail\Services\Content\MergeSubjectService;
use Modules\Mail\Services\Messages\DispatchMessage;

class MessagesController extends Controller
{
    /** @var MessageTenantRepositoryInterface */
    protected $messageRepo;

    /** @var DispatchMessage */
    protected $dispatchMessage;

    /** @var MergeContentService */
    protected $mergeContentService;

    /** @var MergeSubjectService */
    protected $mergeSubjectService;


    private int $workspaceID = 1;

    public function __construct(
        MessageTenantRepositoryInterface $messageRepo,
        DispatchMessage $dispatchMessage,
        MergeContentService $mergeContentService,
        MergeSubjectService $mergeSubjectService
    ) {
        $this->messageRepo = $messageRepo;
        $this->dispatchMessage = $dispatchMessage;
        $this->mergeContentService = $mergeContentService;
        $this->mergeSubjectService = $mergeSubjectService;
    }

    /**
     * Show all sent messages.
     *
     * @throws Exception
     */
    public function index(): View
    {
        $params = request()->only(['search', 'status']);

        $params['sent'] = false;

        $messages = $this->messageRepo->paginateWithSource(
            $this->workspaceID,
            'sent_atDesc',
            [],
            50,
            $params
        );

        return view('mail::backend.messages.index', compact('messages'));
    }

    /**
     * Show draft messages.
     *
     * @throws Exception
     */
    public function draft(): View
    {
        $params = request()->only(['search', 'status']);
        $params['status'] = "draft";

        $messages = $this->messageRepo->paginateWithSource(
            $this->workspaceID,
            'sent_atDesc',
            [],
            50,
            $params
        );

        return view('mail::backend.messages.index', compact('messages'));
    }

    /**
     * Show a single message.
     *
     * @throws Exception
     */
    public function show(int $messageId): View
    {
        $message = $this->messageRepo->find($this->workspaceID, $messageId);

        $content = $this->mergeContentService->handle($message);
        $subject = $this->mergeSubjectService->handle($message);

        return view('mail::backend.messages.show', compact('content', 'message', 'subject'));
    }

    /**
     * Send a message.
     *
     * @throws Exception
     */
    public function send(): RedirectResponse
    {
        if (! $message = $this->messageRepo->find(
            $this->workspaceID,
            request('id'),
            ['subscriber']
        )) {
            return redirect()->back()->withErrors(__('Unable to locate that message'));
        }

        if ($message->sent_at) {
            return redirect()->back()->withErrors(__('The selected message has already been sent'));
        }

        $this->dispatchMessage->handle($message);

        return redirect()->route('backend.messages.draft')->with(
            'success',
            __('The message was sent successfully.')
        );
    }

    /**
     * Send a message.
     *
     * @throws Exception
     */
    public function delete(): RedirectResponse
    {
        if (! $message = $this->messageRepo->find(
            $this->workspaceID,
            request('id')
        )) {
            return redirect()->back()->withErrors(__('Unable to locate that message'));
        }

        if ($message->sent_at) {
            return redirect()->back()->withErrors(__('A sent message cannot be deleted'));
        }

        $this->messageRepo->destroy(
            $this->workspaceID,
            $message->id
        );

        return redirect()->route('backend.messages.draft')->with(
            'success',
            __('The message was deleted')
        );
    }

    /**
     * Send multiple messages.
     *
     * @throws Exception
     */
    public function sendSelected(): RedirectResponse
    {
        if (! request()->has('messages')) {
            return redirect()->back()->withErrors(__('No messages selected'));
        }

        if (! $messages = $this->messageRepo->getWhereIn(
            $this->workspaceID,
            request('messages'),
            ['subscriber']
        )) {
            return redirect()->back()->withErrors(__('Unable to locate messages'));
        }

        $messages->each(function (Message $message) {
            if ($message->sent_at) {
                return;
            }

            $this->dispatchMessage->handle($message);
        });

        return redirect()->route('backend.messages.draft')->with(
            'success',
            __('The messages were sent successfully.')
        );
    }
}
