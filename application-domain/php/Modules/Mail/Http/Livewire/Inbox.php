<?php

declare(strict_types=1);

namespace Modules\Mail\Http\Livewire;

use Livewire\Component;
use Modules\Mail\Services\Inbox\MailContract;

class Inbox extends Component
{
    public $folders = [];
    public  $currentFolder = 'INBOX';
    public  $messages = [];
    public  $selectedMessage = [];


    public function mount()
    {
        $this->loadFolders();
        $this->loadMessages();
    }

    public function loadFolders()
    {
        $mailContract = app(MailContract::class);
        $this->folders = $mailContract->getFolders()->toArray();

    }

    public function loadMessages()
    {
        $mailContract = app(MailContract::class);
        $this->messages = $mailContract->getMessages($this->currentFolder)->toArray();
        $this->selectedMessage = []; // Reset selection
    }

    public function selectFolder(string $folder)
    {
        $this->currentFolder = $folder;
        $this->loadMessages();
    }

    public function selectMessage(string $uid)
    {
        $mailContract = app(MailContract::class);
        $message = $mailContract->getMessage($this->currentFolder, $uid);
        $this->selectedMessage = $message ? $message->toArray() : [];
    }

    public function render()
    {
        return view('mail::backend.inbox.inbox-livewire');
    }
}
