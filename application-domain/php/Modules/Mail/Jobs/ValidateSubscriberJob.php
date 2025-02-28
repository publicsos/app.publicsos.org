<?php
declare(strict_types=1);
namespace Modules\Mail\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Arr;

use Modules\Mail\Repositories\Subscribers\SubscriberTenantRepositoryInterface;
use Modules\Mail\Services\Validation\ValidationService;

class ValidateSubscriberJob implements ShouldQueue
{

    /** @var SubscriberTenantRepositoryInterface */
    private SubscriberTenantRepositoryInterface $subscribers;

    private int $workspaceId;

    private array $data;

    public function __construct(SubscriberTenantRepositoryInterface $subscribers, int $workspaceId, array $data)
    {
        $this->subscribers = $subscribers;
        $this->workspaceId = $workspaceId;
        $this->data = $data;
    }

    /**
     * @throws \Exception
     */
    public function handle():void
    {

        $validateService = new ValidationService();

        $isValid = $validateService->isValidEmail($this->data['email']);

        if(!$isValid) {
            info('Subscriber cannot be validated so skiping import');
            return;
        }

        try {

            $subscriber = null;

            if (! $subscriber) {
                $subscriber = $this->subscribers->findBy($this->workspaceId, 'email', Arr::get($this->data, 'email'), ['tags']);
            }

            if (! $subscriber) {
                $subscriber = $this->subscribers->store($this->workspaceId, Arr::except($this->data, ['id', 'tags']));
            }

            // validate


            $this->data['tags'] = array_merge($subscriber->tags->pluck('id')->toArray(), Arr::get($this->data, 'tags') ?? []);

            $this->subscribers->update($this->workspaceId, $subscriber->id, $this->data);

            info('Subscriber has been updated');

            info("Subscriber has been updated the id is " . $subscriber->id);

        }catch (\Throwable $e) {
            info('Error in Subscriber ' . $e->getMessage());
            info($e->getTraceAsString());
        }
    }
}
