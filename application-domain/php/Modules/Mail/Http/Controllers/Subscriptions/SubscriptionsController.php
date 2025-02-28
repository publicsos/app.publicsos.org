<?php

declare(strict_types=1);

namespace Modules\Mail\Http\Controllers\Subscriptions;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Mail\Http\Controllers\Controller;
use Modules\Mail\Http\Requests\SubscriptionToggleRequest;
use Modules\Mail\Models\Message;
use Modules\Mail\Models\UnsubscribeEventType;
use Modules\Mail\Repositories\Messages\MessageTenantRepositoryInterface;

class SubscriptionsController extends Controller
{
    protected MessageTenantRepositoryInterface $messages;

    private int $workspaceID = 1;

    public function __construct(MessageTenantRepositoryInterface $messages)
    {
        $this->messages = $messages;
    }


    public function unsubscribe(string $messageHash): View
    {
        $message = Message::with('subscriber')->where('hash', $messageHash)->first();

        return view('mail::backend.subscriptions.unsubscribe', compact('message'));
    }

    public function subscribe(string $messageHash): View
    {
        $message = Message::with('subscriber')->where('hash', $messageHash)->first();

        return view('mail::backend.subscriptions.subscribe', compact('message'));
    }


    public function update(SubscriptionToggleRequest $request, string $messageHash): RedirectResponse
    {
        $message = Message::where('hash', $messageHash)->first();

        $subscriber = $message->subscriber;

        $unsubscribed = (bool)$request->get('unsubscribed');

        if ($unsubscribed) {
            $message->unsubscribed_at = now();
            $message->save();

            $subscriber->unsubscribed_at = now();
            $subscriber->unsubscribe_event_id = UnsubscribeEventType::MANUAL_BY_SUBSCRIBER;
            $subscriber->save();

            return redirect()->route('backend.subscriptions.subscribe', $message->hash)
                ->with('success', __('You have been successfully removed from the mailing list.'));
        }

        $message->unsubscribed_at = null;
        $message->save();

        $subscriber->unsubscribed_at = null;
        $subscriber->unsubscribe_event_id = null;
        $subscriber->save();

        return redirect()->route('backend.subscriptions.unsubscribe', $message->hash)
            ->with('success', __('You have been added to the mailing list.'));
    }
}
