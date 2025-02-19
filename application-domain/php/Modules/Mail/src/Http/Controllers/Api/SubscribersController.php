<?php

declare(strict_types=1);

namespace LaravelCompany\Mail\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use LaravelCompany\Mail\Facades\LaravelMail;
use LaravelCompany\Mail\Http\Controllers\Controller;
use LaravelCompany\Mail\Http\Requests\Api\SubscriberStoreRequest;
use LaravelCompany\Mail\Http\Requests\Api\SubscriberUpdateRequest;
use LaravelCompany\Mail\Http\Resources\Subscriber as SubscriberResource;
use LaravelCompany\Mail\Repositories\Subscribers\SubscriberTenantRepositoryInterface;
use LaravelCompany\Mail\Services\Subscribers\ApiSubscriberService;

class SubscribersController extends Controller
{
    /** @var SubscriberTenantRepositoryInterface */
    protected $subscribers;

    /** @var ApiSubscriberService */
    protected $apiService;

    public function __construct(
        SubscriberTenantRepositoryInterface $subscribers,
        ApiSubscriberService $apiService
    ) {
        $this->subscribers = $subscribers;
        $this->apiService = $apiService;
    }

    /**
     * @throws Exception
     */
    public function index(): AnonymousResourceCollection
    {
        $workspaceId = LaravelMail::currentWorkspaceId();

        $subscribers = $this->subscribers->paginate($workspaceId, 'last_name');

        return SubscriberResource::collection($subscribers);
    }

    /**
     * @throws Exception
     */
    public function store(SubscriberStoreRequest $request): SubscriberResource
    {
        $workspaceId = LaravelMail::currentWorkspaceId();

        $subscriber = $this->apiService->storeOrUpdate($workspaceId, collect($request->validated()));

        $subscriber->load('tags');

        return new SubscriberResource($subscriber);
    }

    /**
     * @throws Exception
     */
    public function show(int $id): SubscriberResource
    {
        $workspaceId = LaravelMail::currentWorkspaceId();

        return new SubscriberResource($this->subscribers->find($workspaceId, $id, ['tags']));
    }

    /**
     * @throws Exception
     */
    public function update(SubscriberUpdateRequest $request, int $id): SubscriberResource
    {
        $workspaceId = LaravelMail::currentWorkspaceId();
        $subscriber = $this->subscribers->update($workspaceId, $id, $request->validated());

        return new SubscriberResource($subscriber);
    }

    /**
     * @throws Exception
     */
    public function destroy(int $id): Response
    {
        $workspaceId = LaravelMail::currentWorkspaceId();
        $this->apiService->delete($workspaceId, $this->subscribers->find($workspaceId, $id));

        return response(null, 204);
    }



    /**
     * Enriches a subscriber with the data from various apis
     */
    public function enrich(int $id): mixed
    {
        $workspaceId = LaravelMail::currentWorkspaceId();

        $subscriber = $this->subscribers->find($workspaceId, $id);

        $response = $this->apiService->enrich($subscriber->email);

        dd($response);

        return response(null, 204);
    }


}


