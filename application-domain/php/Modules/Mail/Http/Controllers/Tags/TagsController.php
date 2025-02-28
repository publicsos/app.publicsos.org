<?php

declare(strict_types=1);

namespace Modules\Mail\Http\Controllers\Tags;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Modules\Mail\Http\Controllers\Controller;
use Modules\Mail\Http\Requests\TagStoreRequest;
use Modules\Mail\Http\Requests\TagUpdateRequest;
use Modules\Mail\Repositories\TagTenantRepository;
use Modules\Mail\Models\Subscriber;
class TagsController extends Controller
{
    /** @var TagTenantRepository */
    private TagTenantRepository $tagRepository;

    private int $workspaceId = 1;

    public function __construct(TagTenantRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;

    }

    public function index(): View
    {
        $tags = $this->tagRepository->paginate($this->workspaceId, 'name');

        return view('mail::backend.tags.index', compact('tags'));
    }

    public function create(): View
    {
        $subscribers = Subscriber::all(); // Adjust model name if necessary

        return view('mail::backend.tags.create', compact('subscribers'));
    }


    public function store(TagStoreRequest $request): RedirectResponse
    {
        $this->tagRepository->store($this->workspaceId, $request->all());

        return redirect()->route('backend.tags.index');
    }


    public function edit(int $id): View
    {
        $tag = $this->tagRepository->find($this->workspaceId, $id, ['subscribers']);

        return view('mail::backend.tags.edit', compact('tag'));
    }


    public function update(int $id, TagUpdateRequest $request): RedirectResponse
    {
        $this->tagRepository->update($this->workspaceId, $id, $request->all());

        return redirect()->route('backend.tags.index');
    }


    public function destroy(int $id): RedirectResponse
    {
        $this->tagRepository->destroy($this->workspaceId, $id);

        return redirect()->route('backend.tags.index');
    }
}
