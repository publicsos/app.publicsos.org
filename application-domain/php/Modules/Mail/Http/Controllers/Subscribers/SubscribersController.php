<?php

declare(strict_types=1);

namespace Modules\Mail\Http\Controllers\Subscribers;

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
use Modules\Mail\Events\SubscriberAddedEvent;
use Modules\Mail\Facades\LaravelMail;
use Modules\Mail\Http\Controllers\Controller;
use Modules\Mail\Http\Requests\SubscriberRequest;
use Modules\Mail\Models\UnsubscribeEventType;
use Modules\Mail\Repositories\Subscribers\SubscriberTenantRepositoryInterface;
use Modules\Mail\Repositories\TagTenantRepository;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Modules\Mail\Services\Subscribers\ApiSubscriberService;

class SubscribersController extends Controller
{

    private SubscriberTenantRepositoryInterface $subscriberRepo;


    private TagTenantRepository $tagRepo;

    private int $workspaceId = 1;


    public function __construct(SubscriberTenantRepositoryInterface $subscriberRepo, TagTenantRepository $tagRepo)
    {
        $this->subscriberRepo = $subscriberRepo;

        $this->tagRepo = $tagRepo;

    }

    /**
     * @throws Exception
     */
    public function index(): View
    {


        $subscribers = $this->subscriberRepo->paginate(
            $this->workspaceId,
            'email',
            ['tags'],
            50,
            request()->all()
        )->withQueryString();

        $tags = $this->tagRepo->pluck($this->workspaceId, 'name', 'id');

        return view('mail::backend.subscribers.index', compact('subscribers', 'tags'));
    }

    /**
     * @throws Exception
     */
    public function create(): View
    {
        $tags = $this->tagRepo->pluck($this->workspaceId);
        $selectedTags = [];

        return view('mail::backend.subscribers.create', compact('tags', 'selectedTags'));
    }


    public function store(SubscriberRequest $request): RedirectResponse
    {
        $data = $request->all();
        $data['unsubscribed_at'] = $request->has('subscribed') ? null : now();
        $data['unsubscribe_event_id'] = $request->has('subscribed') ? null : UnsubscribeEventType::MANUAL_BY_ADMIN;

        $subscriber = $this->subscriberRepo->store($this->workspaceId, $data);

        event(new SubscriberAddedEvent($subscriber));

        return redirect()->route('backend.subscribers.index');
    }


    public function show($id): View
    {
        $subscriber = $this->subscriberRepo->find(
            $this->workspaceId,
            $id,
            ['tags', 'messages.source']
        );

        return view('mail::backend.subscribers.show', compact('subscriber'));
    }

    public function edit(int $id): View
    {
        $subscriber = $this->subscriberRepo->find($this->workspaceId, $id);
        $tags = $this->tagRepo->pluck($this->workspaceId);
        $selectedTags = $subscriber->tags->pluck('name', 'id');

        return view('mail::backend.subscribers.edit', compact('subscriber', 'tags', 'selectedTags'));
    }


    public function update(SubscriberRequest $request, int $id): RedirectResponse
    {

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

        return redirect()->route('backend.subscribers.index');
    }

    /**
     * @throws Exception
     */
    public function destroy($id)
    {
        $subscriber = $this->subscriberRepo->find($this->workspaceId, $id);

        $subscriber->delete();

        return redirect()->route('backend.subscribers.index')->withSuccess('Subscriber deleted');
    }

    /**
     * @return string|StreamedResponse
     * @throws Exception
     */
    public function export(): string|StreamedResponse
    {
        $subscribers = $this->subscriberRepo->all($this->workspaceId, 'id');

        if (! $subscribers->count()) {
            return redirect()->route('backend.subscribers.index')->withErrors(__('There are no subscribers to export'));
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
        return view('mail::backend.subscribers.validate');

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

    public function unsubscribe(int $subscriberId): void
    {

        $subscriber = $this->subscriberRepo->find(1, $subscriberId);

    }



    public function importer()
    {
        return view('mail::backend.csv-importer.index');
    }


    public function storeImport()
    {
        $file = request()->file('file');

        if (! $file) {
            return redirect()->back()->withErrors(['file' => 'File is required']);
        }

        $fileName = $file->getClientOriginalName();

        $filePath = $file->store('csv-imports');

        $import = LaravelMail::importCsv($filePath);

        return redirect()->back()->with('success', 'Imported ' . $import->imported . ' records');
    }
}
