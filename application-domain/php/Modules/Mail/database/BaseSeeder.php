<?php

declare(strict_types=1);

namespace Modules\Mail\database;

use App\Models\ApiToken;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Modules\Mail\DataBuses\ValueResource;
use Modules\Mail\Loggers\WorkflowLog;
use Modules\Mail\Models\Campaign;
use Modules\Mail\Models\EmailService;
use Modules\Mail\Models\EmailServiceType;
use Modules\Mail\Models\Subscriber;
use Modules\Mail\Models\Template;
use Modules\Mail\Models\Workflow;
use Modules\Mail\Tasks\RssReader;
use Modules\Mail\Tasks\Task;
use Modules\Mail\Triggers\ObserverTrigger;

class BaseSeeder extends Seeder
{
    private const DEFAULT_EMAIL = 'notificare@furaciuni.ro';
    private const DEFAULT_PASSWORD = 'secret';
    private const DEFAULT_NAME = 'Stefan';
    private const DEFAULT_SUBJECT = 'Cautam parteneri pentru a crea un nou flux de email';
    private const RSS_FEED_URL = 'https://feed.laravel-news.com/';

    private int $workspace_id;
    private string $email;
    private string $password;
    private string $name;
    private string $subject;

    public string $content;

    public function __construct()
    {
        $this->email = self::DEFAULT_EMAIL;
        $this->password = self::DEFAULT_PASSWORD;
        $this->name = self::DEFAULT_NAME;
        $this->subject = self::DEFAULT_SUBJECT;
        $this->content = "
Sorry for the direct contact.
My name is Stefan and I'm a developer just like you.
The reason why I'm contacting you is to introduce Furaciuni.ro.
It's a multi channel marketing platform that you can self host that is built with the Laravel Framework.
I'm searching for fellow developers to help me add more features to the platform for a cut from the sales.
Currently we have over 200k users testing the platform and we are looking for new developers to help us add more features.
If you are interested in joining me as a Co-Founder you can setup a meeting on this link.
I'm searching for a person to help me on the marketing side / design of the platform.

<a href='https://calendly.com/izdrail'>Book online meeting</a>

Best regards,

Stefan Bogdanel
Founder

";
    }

    public function createWorkspace(array $data, User $user): Workspace
    {
        $workspace = Workspace::factory()->create($data);

        $this->createApiToken($workspace);

        return $workspace;
    }

    private function createApiToken(Workspace $workspace): void
    {
        ApiToken::factory()->create([
            'workspace_id' => $workspace->id,
            'api_token' => Str::random(80),
        ]);
    }

     /**
     * Create a new email service for the workspace.
     *
     * @param Workspace $workspace
     * @param int $serviceTypeId
     * @param array $settings
     * @return EmailService
     * @throws \InvalidArgumentException
     */
    public function createEmailService(
        Workspace $workspace,
        int $serviceTypeId,
        array $settings = []
    ): EmailService {
        if (!EmailServiceType::resolve($serviceTypeId)) {
            throw new \InvalidArgumentException('Invalid email service type provided.');
        }

        $serviceConfig = EmailServiceType::getServiceConfig($serviceTypeId, $settings);

        $data = [
            'name' => EmailServiceType::resolve($serviceTypeId),
            'settings' => $serviceConfig,
            'type_id' => $serviceTypeId,
            'workspace_id' => $workspace->id
        ];

        return EmailService::factory()->create($data);
    }


    public function createTemplate(
        Workspace $workspace,
        string $name,
        string $content
    ): Template {
        return Template::factory()->create([
            'name' => $name,
            'slug' => Str::slug($name),
            'workspace_id' => $workspace->id,
            'content' => $content,
        ]);
    }

    public function createCampaign(
        Workspace $workspace,
        Template $template,
        EmailService $emailService
    ): Campaign {
        return Campaign::factory()->create([
            'name' => $this->generateCampaignName($workspace, $emailService),
            'workspace_id' => $workspace->id,
            'template_id' => $template->id,
            'email_service_id' => $emailService->id,
            'subject' => $this->subject,
            'from_name' => $this->name,
            'from_email' => $this->email,
            'content' => $this->content,
            'send_to_all' => 1,
        ]);
    }

    private function generateCampaignName(Workspace $workspace, EmailService $emailService): string
    {
        return sprintf(
            "Campaign %s - %s",
            $workspace->name,
            $emailService->name
        );
    }

    public function createBaseSetupForWorkflows(Workspace $workspace): Workflow
    {
        $workflow = $this->createWorkflow($workspace);
        $trigger = $this->createTrigger($workflow->id);
        $tasks = $this->getDefaultTasks();

        $this->createTaskChain($workflow, $trigger, $tasks);
        $this->logWorkflowCreation($workflow, $trigger);

        return $workflow;
    }

    private function createWorkflow(Workspace $workspace): Workflow
    {
        return Workflow::create([
            'name' => 'New campaign created',
            'workspace_id' => $workspace->id,
        ]);
    }

    private function createTrigger(int $workflowId): ObserverTrigger
    {
        return ObserverTrigger::create([
            'type' => ObserverTrigger::class,
            'name' => 'ObserverTrigger',
            'queueable' => true,
            'data_fields' => $this->getTriggerDataFields(),
            'workflow_id' => $workflowId,
            'pos_x' => 100,
            'pos_y' => 10,
        ]);
    }

    private function getTriggerDataFields(): string
    {
        return json_encode([
            'class' => [
                'value' => Campaign::class,
                'type' => ValueResource::class,
            ],
            'description' => [

                'value' => 'When a new campaign is created',
            ],
        ]);
    }

    private function getDefaultTasks(): array
    {
        return [
            [
                'name' => 'RssReader',
                'type' => RssReader::class,
                'data_fields' => $this->getRssReaderDataFields(),
                'pos_x' => 500,
                'pos_y' => 100,
            ]
        ];
    }

    private function getRssReaderDataFields(): string
    {
        return json_encode([
            'url' => [
                'type' => ValueResource::class,
                'value' => self::RSS_FEED_URL,
            ],
            'description' => [
                'value' => 'Extracts all the entries from the rss feed',
            ],
            'output' => [
                'value' => 'Rss Reader output',
            ],
        ]);
    }

    private function createTaskChain(
        Workflow $workflow,
        ObserverTrigger $parentTask,
        array $tasks
    ): void {
        foreach ($tasks as $index => $taskData) {
            $task = $this->createTask($workflow->id, $taskData, $index + 1);
            $this->linkTaskToParent($task, $parentTask);
            $parentTask = $task;
        }
    }

    private function createTask(
        int $workflowId,
        array $taskData,
        int $nodeId
    ): Task {
        return Task::create([
            'name' => $taskData['name'],
            'workflow_id' => $workflowId,
            'type' => $taskData['type'],
            'data_fields' => $taskData['data_fields'],
            'node_id' => $nodeId,
            'pos_x' => $taskData['pos_x'],
            'pos_y' => $taskData['pos_y'],
        ]);
    }

    private function linkTaskToParent(Task $task, $parentTask): void
    {
        $task->parentable_id = $parentTask->id;
        $task->parentable_type = get_class($parentTask);
        $task->save();
    }

    private function logWorkflowCreation(
        Workflow $workflow,
        ObserverTrigger $trigger
    ): void {
        WorkflowLog::createHelper($workflow, $workflow, $trigger);
    }
}
