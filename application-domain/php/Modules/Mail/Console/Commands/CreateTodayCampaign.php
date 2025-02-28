<?php

namespace Modules\Mail\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Workspace;
use Modules\Mail\database\LaravelMail;
use Database\Seeders\TodayCampaignSeeder;
use Modules\Mail\Models\{Template, Campaign, EmailService, EmailServiceType, Tag};
use Modules\Mail\Services\Subscribers\ImportSubscriberService;
use Illuminate\Support\Facades\{Config, Log};
use RuntimeException;

class CreateTodayCampaign extends Command
{
    protected $signature = 'campaigns:create
                          {--dry-run : Run without making any changes}';

    protected $description = 'Create today\'s campaign with specified template or default template';

    protected LaravelMail $db;

    public function __construct(LaravelMail $db)
    {
        parent::__construct();

        $this->db = $db;
    }

    public function handle()
    {
        $this->info('Creating today\'s campaign...');

        $templateID = $this->ask('Please select a template to use for the campaign');


        $workspace = Workspace::find(1);

        $emailService = EmailService::find(1);

        $template = Template::find($templateID);

        $campaign = $this->db->createCampaign($workspace, $template, $emailService);

        $this->info('Campaign created!');

        $this->db->createSubscribers($workspace, $campaign);

        $this->info('Subscribers created!');
    }



}
