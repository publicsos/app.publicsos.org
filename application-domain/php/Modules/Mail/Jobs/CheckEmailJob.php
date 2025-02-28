<?php
declare(strict_types=1);
namespace Modules\Mail\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

use Modules\Mail\Models\Subscriber;
use Modules\Mail\Services\Validation\ValidationContract;
use Illuminate\Support\Facades\Log;

class CheckEmailJob implements ShouldQueue
{
    use Queueable;

    private ValidationContract $validateService;

    private Subscriber $subscriber;

    public function __construct(Subscriber $subscriber)
    {
        $this->subscriber = $subscriber;
        $this->validateService = app(ValidationContract::class);

    }

    /**
     * @throws \Exception
     */
    public function handle():void
    {

        $isValid = $this->validateService->isValidEmail($this->subscriber->email);

        if($isValid)
        {

            tap($this->subscriber)->update([
                'meta' => [
                    'valid' => $isValid
                ]
            ]);

            Log::info("Subscriber {$this->subscriber->email} is valid");
        }

        tap($this->subscriber)->update([
            'meta' => [
                'valid' => $isValid
            ],
            'unsubscribed_at' => now(),
            'unsubscribe_event_id' => 1,
        ]);

        Log::info("Subscriber {$this->subscriber->email} is invalid");

    }
}
