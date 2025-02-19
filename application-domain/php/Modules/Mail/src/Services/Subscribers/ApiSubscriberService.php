<?php

declare(strict_types=1);

namespace LaravelCompany\Mail\Services\Subscribers;

use App\Services\Intelligence\Saloon\DTO\ScanResult;
use App\Services\Intelligence\Saloon\DTO\ScanSummary;
use App\Services\Intelligence\SpiderFoot;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use LaravelCompany\Mail\Events\SubscriberAddedEvent;
use LaravelCompany\Mail\Models\Subscriber;
use LaravelCompany\Mail\Repositories\Subscribers\SubscriberTenantRepositoryInterface;


class ApiSubscriberService
{
    /** @var SubscriberTenantRepositoryInterface */
    private SubscriberTenantRepositoryInterface $subscribers;

    /** @var SpiderFoot */
    private SpiderFoot $enrich;

    public function __construct(SubscriberTenantRepositoryInterface $subscribers, SpiderFoot $enrich)
    {
        $this->subscribers = $subscribers;
        $this->enrich = $enrich;
    }

    /**
     * The API provides the ability for the "store" endpoint to both create a new subscriber or update an existing
     * subscriber, using their email as the key. This method allows us to handle both scenarios.
     *
     * @throws Exception
     */
    public function storeOrUpdate(int $workspaceId, Collection $data): Subscriber
    {
        $existingSubscriber = $this->subscribers->findBy($workspaceId, 'email', $data['email']);

        if (! $existingSubscriber) {
            $subscriber = $this->subscribers->store($workspaceId, $data->toArray());

            event(new SubscriberAddedEvent($subscriber));

            return $subscriber;
        }

        return $this->subscribers->update($workspaceId, $existingSubscriber->id, $data->toArray());
    }

    public function delete(int $workspaceId, Subscriber $subscriber): bool
    {
        return DB::transaction(function () use ($workspaceId, $subscriber) {
            $subscriber->tags()->detach();
            return $this->subscribers->destroy($workspaceId, $subscriber->id);
        });
    }


    public function enrich(string $target): ScanResult
    {
        return $this->enrich->startScan($target);
    }

    //should be done by subscriber mail address
    public function getEnriched(string $scanId): ScanSummary
    {
        return $this->enrich->getResults($scanId);
    }
}
