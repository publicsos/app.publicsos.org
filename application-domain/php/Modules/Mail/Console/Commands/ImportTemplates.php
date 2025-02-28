<?php
declare(strict_types=1);
namespace Modules\Mail\Console\Commands;



use Modules\Mail\Services\Templates\Contracts\TemplateContract;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Modules\Mail\Facades\LaravelMail;
use Modules\Mail\Repositories\TemplateTenantRepository;

class ImportTemplates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-templates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import templates from the unilayer to the tenant';


    public function __construct(
        private readonly TemplateContract $templatesManager,
        private readonly TemplateTenantRepository $templateRepository
    )
    {
        parent::__construct();
    }


    /**
     * Execute the console command.
     * @throws \Exception
     */
    public function handle(): void
    {
        $templates = $this->templatesManager->getTemplates();

        $bar = $this->output->createProgressBar($templates->count());

        $bar->start();

        $templates->each(function ($template) use ($bar, $templates) {

            //https://api.unlayer.com/v2/stock-templates/labor-day-parade/thumbnail
            //https://


            $bar->advance();

            $this->info('Getting template: ' . $template['name']);

            $response = $this->templatesManager->getTemplate($template['slug']);



            $this->save($response,  $template);

        });
    }

    /**
     * @param array $template
      * @throws \Exception
     */
    private function save(string $response, array $template): void
    {
        //dd($template);

        $slug = $template['slug'];
        $this->info('Saving template:  '  .  $slug);
        $this->info('Saving template: ' . $slug);

        // Define paths
        $templateFilePath = resource_path('templates/' . $slug . '.html');

        $thumbnailUrl = "https://api.unlayer.com/v2/stock-templates/$slug/thumbnail";

        $thumbnailFilePath = public_path('thumbnails/' . $slug . '.jpg');

        // Ensure the directories exist
        if (!File::exists(dirname($templateFilePath))) {
            File::makeDirectory(dirname($templateFilePath), 0755, true);
        }
        if (!File::exists(dirname($thumbnailFilePath))) {
            File::makeDirectory(dirname($thumbnailFilePath), 0755, true);
        }

        // Save the template content
        File::put($templateFilePath, $response);

        // Download and save the thumbnail
        try {
            $this->info('Downloading thumbnail...');
            $thumbnailContent = file_get_contents($thumbnailUrl);
            if ($thumbnailContent === false) {
                throw new \Exception("Failed to download thumbnail from $thumbnailUrl");
            }

            File::put($thumbnailFilePath, $thumbnailContent);

        } catch (\Exception $e) {
            $this->error("Error saving thumbnail: " . $e->getMessage());
            // Optionally handle the error (e.g., log it or continue without saving the thumbnail)
        }

        //todo thumbnail
        $template['thumbnail'] =  ('thumbnails/' . $slug . '.jpg');
        $template['slug'] = $slug;
        $template['content'] = $response;

        unset($template['id']);
        //dd($template);  ("id", "premium", "rating", "previewUrl", "name", "slug", "votes", "type", "thumbnail", "workspace_id", "updated_at", "created_at")

        // Save to the database
        $this->templateRepository->store(LaravelMail::currentWorkspaceId(), $template);

        $this->info("Template '$slug' saved successfully with thumbnail.");
    }

}
