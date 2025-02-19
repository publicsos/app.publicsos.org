<?php

declare(strict_types=1);

namespace LaravelCompany\Mail\Http\Controllers\Subscribers;

use App\Models\Intelligence;
use AWS\CRT\HTTP\Request;
use Box\Spout\Common\Exception\InvalidArgumentException;
use Box\Spout\Common\Exception\IOException;
use Box\Spout\Common\Exception\UnsupportedTypeException;
use Box\Spout\Writer\Exception\WriterNotOpenedException;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Rap2hpoutre\FastExcel\FastExcel;
use LaravelCompany\Mail\Events\SubscriberAddedEvent;
use LaravelCompany\Mail\Facades\LaravelMail;
use LaravelCompany\Mail\Http\Controllers\Controller;
use LaravelCompany\Mail\Http\Requests\SubscriberRequest;
use LaravelCompany\Mail\Models\UnsubscribeEventType;
use LaravelCompany\Mail\Repositories\Subscribers\SubscriberTenantRepositoryInterface;
use LaravelCompany\Mail\Repositories\TagTenantRepository;
use Symfony\Component\HttpFoundation\StreamedResponse;
use LaravelCompany\Mail\Services\Subscribers\ApiSubscriberService;

class SubscribersController extends Controller
{
    /** @var SubscriberTenantRepositoryInterface */
    private SubscriberTenantRepositoryInterface $subscriberRepo;

    /** @var TagTenantRepository */
    private TagTenantRepository $tagRepo;

    private int $workspaceId;


    public function __construct(SubscriberTenantRepositoryInterface $subscriberRepo, TagTenantRepository $tagRepo)
    {
        $this->subscriberRepo = $subscriberRepo;

        $this->tagRepo = $tagRepo;

        $this->workspaceId = LaravelMail::currentWorkspaceId();
    }

    /**
     * @throws Exception
     */
    public function index(): View
    {

        //todo improve this to check if an email address was enriched or not this will have an
        $this->workspaceId = LaravelMail::currentWorkspaceId();

        $subscribers = $this->subscriberRepo->paginate(
            $this->workspaceId,
            'email',
            ['tags'],
            50,
            request()->all()
        )->withQueryString();

        $tags = $this->tagRepo->pluck($this->workspaceId, 'name', 'id');

        return view('laravel-mail::subscribers.index', compact('subscribers', 'tags'));
    }

    /**
     * @throws Exception
     */
    public function create(): View
    {
        $this->workspaceId = LaravelMail::currentWorkspaceId();
        $tags = $this->tagRepo->pluck($this->workspaceId);
        $selectedTags = [];

        return view('laravel-mail::subscribers.create', compact('tags', 'selectedTags'));
    }

    /**
     * @throws Exception
     */
    public function store(SubscriberRequest $request): RedirectResponse
    {
        $this->workspaceId = LaravelMail::currentWorkspaceId();
        $data = $request->all();
        $data['unsubscribed_at'] = $request->has('subscribed') ? null : now();
        $data['unsubscribe_event_id'] = $request->has('subscribed') ? null : UnsubscribeEventType::MANUAL_BY_ADMIN;

        $subscriber = $this->subscriberRepo->store($this->workspaceId, $data);

        event(new SubscriberAddedEvent($subscriber));

        return redirect()->route('laravel-mail.subscribers.index');
    }

    /**
     * @throws Exception
     */
    public function show(int $id): View
    {
        $this->workspaceId = LaravelMail::currentWorkspaceId();
        $subscriber = $this->subscriberRepo->find(
            $this->workspaceId,
            $id,
            ['tags', 'messages.source']
        );

        return view('laravel-mail::subscribers.show', compact('subscriber'));
    }

    /**
     * @throws Exception
     */
    public function edit(int $id): View
    {
        $this->workspaceId = LaravelMail::currentWorkspaceId();
        $subscriber = $this->subscriberRepo->find($this->workspaceId, $id);
        $tags = $this->tagRepo->pluck($this->workspaceId);
        $selectedTags = $subscriber->tags->pluck('name', 'id');

        return view('laravel-mail::subscribers.edit', compact('subscriber', 'tags', 'selectedTags'));
    }

    /**
     * @throws Exception
     */
    public function update(SubscriberRequest $request, int $id): RedirectResponse
    {
        $this->workspaceId = LaravelMail::currentWorkspaceId();
        $subscriber = $this->subscriberRepo->find($this->workspaceId, $id);
        $data = $request->validated();

        // updating subscriber from subscribed -> unsubscribed
        if (! $request->has('subscribed') && ! $subscriber->unsubscribed_at) {
            $data['unsubscribed_at'] = now();
            $data['unsubscribe_event_id'] = UnsubscribeEventType::MANUAL_BY_ADMIN;
        } // updating subscriber from unsubscribed -> subscribed
        elseif ($request->has('subscribed') && $subscriber->unsubscribed_at) {
            $data['unsubscribed_at'] = null;
            $data['unsubscribe_event_id'] = null;
        }

        if (! $request->has('tags')) {
            $data['tags'] = [];
        }

        $this->subscriberRepo->update($this->workspaceId, $id, $data);

        return redirect()->route('laravel-mail.subscribers.index');
    }

    /**
     * @throws Exception
     */
    public function destroy($id)
    {
        $this->workspaceId = LaravelMail::currentWorkspaceId();
        $subscriber = $this->subscriberRepo->find($this->workspaceId, $id);

        $subscriber->delete();

        return redirect()->route('laravel-mail.subscribers.index')->withSuccess('Subscriber deleted');
    }

    /**
     * @return string|StreamedResponse
     * @throws Exception
     */
    public function export(): string|StreamedResponse
    {
        $this->workspaceId = LaravelMail::currentWorkspaceId();
        $subscribers = $this->subscriberRepo->all($this->workspaceId, 'id');

        if (! $subscribers->count()) {
            return redirect()->route('laravel-mail.subscribers.index')->withErrors(__('There are no subscribers to export'));
        }

        return (new FastExcel($subscribers))
            ->download(sprintf('subscribers-%s.csv', date('Y-m-d-H-m-s')), static function ($subscriber) {
                return [
                    'id' => $subscriber->id,
                    'hash' => $subscriber->hash,
                    'email' => $subscriber->email,
                    'first_name' => $subscriber->first_name,
                    'last_name' => $subscriber->last_name,
                    'created_at' => $subscriber->created_at,
                ];
            });
    }


    /**
     * Validate
     */

    public function validateSubscribers()
    {
        //form
        return view('laravel-mail::subscribers.validate');

    }

    /**
     * Enriches a subscriber with the data from various apis
     */
    public function enrich(int $subscriberId, ApiSubscriberService $apiService): mixed
    {


        $workspaceId = LaravelMail::currentWorkspaceId();

        $subscriber = $this->subscriberRepo->find($workspaceId, $subscriberId);

        $response = $apiService->enrich($subscriber->email);


        return redirect()->back()->with('success', 'Subscriber enriched');


    }

    /**
     * Enriches a subscriber with the data from various apis
     */
    public function unsubscribe(int $subscriberId): mixed
    {


        $workspaceId = LaravelMail::currentWorkspaceId();

        $subscriber = $this->subscriberRepo->find($workspaceId, $subscriberId);

        // todo implement unsubscribe manual

    }
}
