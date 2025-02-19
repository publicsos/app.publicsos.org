<?php

namespace LaravelCompany\Mail\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Arr;
use LaravelCompany\Mail\Repositories\Subscribers\SubscriberTenantRepositoryInterface;
use LaravelCompany\Mail\Services\Validation\Contracts\ValidationContract;

class ImportSingleSubscriberJob implements ShouldQueue
{
    use Queueable;

    private SubscriberTenantRepositoryInterface $subscribers;
    private int $workspaceId;
    private array $data;

    public function __construct(int $workspaceId, array $data)
    {
        $this->workspaceId = $workspaceId;
        $this->data = $data;
    }

    /**
     * @throws \Exception
     */
    public function handle(): void
    {
        $this->subscribers = app(SubscriberTenantRepositoryInterface::class);

        $validateService = app(ValidationContract::class);

        try {
            // Validate email before proceeding
            /** @var ValidationContract $validateService */


            $isValid = $validateService->isValidEmail($this->data['email']);

            if (!$isValid) {
                info('Invalid email address: ' . $this->data['email']);
                return; // Exit early if validation fails
            }

            // Check if the subscriber already exists
            $subscriber = $this->subscribers->findBy(
                $this->workspaceId,
                'email',
                Arr::get($this->data, 'email'),
                ['tags']
            );

            // Create a new subscriber if it doesn't exist
            if (!$subscriber) {
                $subscriber = $this->subscribers->store(
                    $this->workspaceId,
                    Arr::except($this->data, ['id', 'tags'])
                );

                ///event(new SubscriberAddedEvent($subscriber));
                //TODO:: DISPATCH A EVENT TO START INTELLIGENCE
            }

            // Merge and update tags
            $this->data['tags'] = array_merge(
                $subscriber->tags->pluck('id')->toArray(),
                Arr::get($this->data, 'tags') ?? []
            );

            // Retrieve existing meta data (default to empty array if null)
            $existingMeta = $subscriber->meta ?? [];

            // Merge new data with existing meta
            $updatedMeta = array_merge($existingMeta, [
                'valid' => true,
            ]);

            $this->subscribers->update($this->workspaceId, $subscriber->id, [
                'tags' => $this->data['tags'],
                'meta' => $updatedMeta,
            ]);

        } catch (\Throwable $e) {
            info('Error: ' . $e->getMessage());
            info($e->getTraceAsString());
        }
    }
}
