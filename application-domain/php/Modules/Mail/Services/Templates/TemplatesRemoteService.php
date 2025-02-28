<?php

declare(strict_types=1);

namespace Modules\Mail\Services\Templates;

#todo - Move this to package
use App\Models\Workspace;


use App\Services\Templates\Contracts\TemplateContract;
use Illuminate\Support\Facades\File;
use Modules\Mail\Models\Template;

/**
 * TODO - Move this to package
 */
class TemplatesRemoteService
{
    public function __construct(private readonly TemplateContract $templatesManager)
    {
    }

    /**
     * Fetch and process templates from the database.
     */
    public function run(): void
    {
        $templates = Template::all();

        $templates->each(function ($template) {
            $workspace = Workspace::find($template->workspace_id);
            $this->logInfo('Processing workspace: ' . json_encode($workspace));
        });

        $templatesFromService = $this->templatesManager->getTemplates();

        $templatesFromService->each(function ($template) {
            $this->logInfo('Getting template: ' . $template['name']);

            $response = $this->templatesManager->getTemplate($template['slug']);
            $this->saveToFile($response, $template['slug']);
        });
    }

    /**
     * Save template content to a file.
     */
    private function saveToFile(string $response, string $name): void
    {
        $filePath = resource_path('views/templates/' . $name . '.html');

        if (!File::exists(dirname($filePath))) {
            File::makeDirectory(dirname($filePath), 0755, true);
        }

        File::put($filePath, $response);
    }

    /**
     * Log information to the console or other medium.
     */
    private function logInfo(string $message): void
    {
        // Replace this with a proper logging mechanism if needed
        info($message);
    }
}
