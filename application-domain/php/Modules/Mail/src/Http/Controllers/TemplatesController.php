<?php

declare(strict_types=1);

namespace LaravelCompany\Mail\Http\Controllers;

use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use LaravelCompany\Mail\Facades\LaravelMail;
use LaravelCompany\Mail\Http\Requests\TemplateStoreRequest;
use LaravelCompany\Mail\Http\Requests\TemplateUpdateRequest;
use LaravelCompany\Mail\Repositories\TemplateTenantRepository;
use LaravelCompany\Mail\Services\Templates\TemplateService;
use LaravelCompany\Mail\Traits\NormalizeTags;
use Throwable;

class TemplatesController extends Controller
{
    use NormalizeTags;

    public function __construct(
        private readonly TemplateTenantRepository $templates,
        private readonly TemplateService $service
    ) {}

    /**
     * Display a paginated list of templates, optionally filtered by search query.
     *
     * @throws Exception
     */
    public function index(Request $request): View
    {
        $workspaceId = LaravelMail::currentWorkspaceId();
        $searchTerm = $searchQuery = $request->get('search');


        $templates = $this->searchTemplates($workspaceId, $searchQuery);

        return view('laravel-mail::templates.index', compact('templates', 'searchQuery', 'searchTerm'));
    }

    /**
     * Show the template creation form.
     */
    public function create(): View
    {
        return view('laravel-mail::templates.create');
    }

    /**
     * Store a newly created template.
     *
     * @throws Exception
     */
    public function store(TemplateStoreRequest $request): RedirectResponse
    {
        $workspaceId = LaravelMail::currentWorkspaceId();
        $this->service->store($workspaceId, $request->validated());

        return redirect()->route('laravel-mail.templates.index');
    }

    /**
     * Show the template edit form.
     *
     * @throws Exception
     */
    public function edit(int $id): View
    {
        $workspaceId = LaravelMail::currentWorkspaceId();
        $template = $this->templates->find($workspaceId, $id);

        return view('laravel-mail::templates.edit', compact('template'));
    }

    /**
     * Update an existing template.
     *
     * @throws Exception
     */
    public function update(TemplateUpdateRequest $request, int $id): RedirectResponse
    {
        $workspaceId = LaravelMail::currentWorkspaceId();
        $this->service->update($workspaceId, $id, $request->validated());

        return redirect()->route('laravel-mail.templates.index');
    }

    /**
     * Delete a template.
     *
     * @throws Throwable
     */
    public function destroy(int $id): RedirectResponse
    {
        $workspaceId = LaravelMail::currentWorkspaceId();
        $this->service->delete($workspaceId, $id);

        return redirect()->route('laravel-mail.templates.index')
            ->with('success', __('Template successfully deleted.'));
    }


    /**
     * Search for templates by name.
     *
     * @throws Exception
     */
    private function searchTemplates(int $workspaceId, ?string $searchQuery)
    {
        $query = $this->templates->getQueryBuilder($workspaceId); // Ensure we're using the query builder

        if ($searchQuery) {
            $query->where('name', 'LIKE', "%{$searchQuery}%");
        }

        return $query->paginate(120); // Adjust pagination as needed
    }

}
