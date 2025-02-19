<?php
declare(strict_types=1);
namespace LaravelCompany\Mail\Http\Controllers\Inbox;

use Illuminate\View\View;
use LaravelCompany\Mail\Http\Controllers\Controller;
use Illuminate\Http\Request;



use LaravelCompany\Mail\Services\Inbox\MailContract;

class InboxController extends Controller
{

    private MailContract $mailService;

    public function __construct(MailContract $mailService)
    {
        $this->mailService = $mailService;
    }


    public function folder(string $currentFolder, Request $request): View
    {
        // Get all folders
        $folders = $this->mailService->getFolders();

        $folder = $currentFolder ;

        $messages = $this->mailService->getMessages($folder);

        $message = $request->query('uid') ? $this->mailService->getMessage($folder, $request->query('uid')) : [];

        $selectedMessage = $request->query('uid')? $messages->find($request->query('uid')) : [];

        return view('laravel-mail::inbox.index', compact('folders', 'folder', 'messages', 'message', 'currentFolder', 'selectedMessage'));
    }


    public function message(string $folder ,string $messageId, Request $request): View
    {
        $folders = $this->mailService->getFolders();

        $message = $this->mailService->getMessage($folder,$messageId);

        return view('laravel-mail::inbox.message', compact('message', 'folders'));
    }

}
