<?php
declare(strict_types=1);
namespace Modules\Mail\Pipelines\Testing;

use Illuminate\Http\Request;
use Modules\Mail\Events\MessageDispatchEvent;
use Modules\Mail\Models\Campaign;
use Modules\Mail\Models\Message;
use Modules\Mail\Models\Subscriber;

class SendMessage
{
    public function handle(Request $request, $next)
    {


        $campaign = Campaign::find($request->campaign);

        $subscriber = Subscriber::where('email', $request->email)->first();


        if (!$subscriber) {
            $saveSubscriber = Subscriber::updateOrCreate([
                'workspace_id' => $campaign->workspace_id,
                'email' => $request->get('email'),

            ]);
            $subscriber = $saveSubscriber;
        }
        $attributes = [
            'workspace_id' => $campaign->workspace_id,
            'subscriber_id' => $subscriber->id,
            'source_type' => Campaign::class,
            'source_id' => $campaign->id,
            'recipient_email' => $subscriber->email,
            'subject' => $campaign->subject,
            'from_name' => $campaign->from_name,
            'from_email' => $campaign->from_email,
            'queued_at' => now(),
            'sent_at' => now(),
        ];



        $message = new Message($attributes);
        $message->save();

        event(new MessageDispatchEvent($message));

        return $next($request);
    }


}
