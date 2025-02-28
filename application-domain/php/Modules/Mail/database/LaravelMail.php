<?php

declare(strict_types=1);

namespace Modules\Mail\database;

use App\Models\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Modules\Mail\Models\Campaign;
use Modules\Mail\Models\Template;
use Modules\Mail\Models\EmailServiceType;
use Modules\Mail\Services\Subscribers\ImportSubscriberService;

use App\Models\Workspace;
use Modules\Mail\Models\Tag;
use RuntimeException;

class LaravelMail extends BaseSeeder
{
    private const SAMPLE_SIZE = 100;
    private const TEMPLATE_FILENAME = 'warm-lead.html';
    private const EMAIL_INDEX = 3;
    private const FIRST_NAME_INDEX = 1;
    private const LAST_NAME_INDEX = 1;

    private readonly string $csvDirectory;
    private readonly string $templatePath;
    private array $defaultUserData;

    private string $email;
    private string $name;

    private ImportSubscriberService $importSubscriberService;

    public function __construct(ImportSubscriberService $importSubscriberService)
    {
        parent::__construct();

        $this->initializeDefaultValues();
        $this->initializePaths();
        $this->setDefaultUserData();

        $this->importSubscriberService = $importSubscriberService;
    }

    private function initializeDefaultValues(): void
    {
        $this->email = 'alert@furaciuni.ro';

        $this->name = 'Furaciuni';

        $this->content = '<div style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
        <p>I hope this email finds you well friend!</p>

        <p>I’m excited to introduce <strong>Laravel Mail</strong>, a powerful and fully featured email marketing platform tailored for creators, developers, and businesses using Laravel.</p>

        <p>Here’s what makes Laravel Mail stand out:</p>
        <ul>
            <li><strong>Effortless Automation:</strong> Built on SendPortal.io and powered by 42Workflows, you can create smart workflows with conditions to simplify your email campaigns.</li>
            <li><strong>Email Intelligence:</strong> Validate and enrich email data with actionable risk scores, fraud protection, and detailed insights.</li>
            <li><strong>Self-Hosted Solution:</strong> Host it on your own VPS, cloud server, or even a Raspberry Pi.</li>
            <li><strong>Unlimited Everything:</strong> Users, subscribers, segments, and campaigns—all unlimited at no extra cost.</li>
            <li><strong>GrapesJS Editor:</strong> Design stunning email templates effortlessly with a drag-and-drop interface.</li>
            <li><strong>Advanced CSV Importer:</strong> Easily import contacts without constraints.</li>
        </ul>

        <p>Whether you are focused on marketing, business automation, finance, or enterprise design, Laravel Mail empowers your team with modern tools to achieve your goals.</p>

        <p>Discover how Laravel Mail compares to other platforms, and explore its features designed to help you grow.</p>

        <p><a href="https://furaciuni.ro" style="color:blue;text-decoration: underline;">Learn more or get started today at Laravel Mail</a>.</p>

        <p>If you’d like a demo or have any questions, feel free to reply—I’d be happy to assist!</p>

        <p>Best regards,</p>
        <p>Stefan Bogdanel<br>Founder<br><a href="https://furaciuni.ro" target="_blank" style="color: blue; text-decoration: underline;">https://furaciuni.ro</a></p>
    </div>';
    }

    private function initializePaths(): void
    {
        $this->csvDirectory = base_path('database/seeders/content/');
        $this->templatePath = resource_path('templates/' . self::TEMPLATE_FILENAME);

        $this->validatePaths();
    }

    private function validatePaths(): void
    {
        if (!is_dir($this->csvDirectory)) {
            throw new RuntimeException("CSV directory not found: {$this->csvDirectory}");
        }

        if (!file_exists($this->templatePath)) {
            throw new RuntimeException("Template file not found: {$this->templatePath}");
        }
    }

    private function setDefaultUserData(): void
    {
        $this->defaultUserData = [
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => now(),
            'password' => $this->getHashedPassword(),
            'current_workspace_id' => null
        ];
    }

    private function getHashedPassword(): string
    {
        $password ="secret";

        if (empty($password)) {
            throw new RuntimeException('Password configuration is missing');
        }

        return bcrypt($password);
    }

    public function run(): void
    {
        try {
            $this->seedData();
            Log::info('Seeding completed successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to seed data: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    private function seedData(): void
    {

        $user = $this->createUser();

        $workspace = $this->setupWorkspace($user);

        $this->createBaseSetupForWorkflows($workspace);

        $smtpSettings = [
            "name" => $this->name,
            "email" => $this->email,
            'host' => config('mail.mailers.smtp.host'),
            'port' => config('mail.mailers.smtp.port'),
            'username' => config('mail.mailers.smtp.username'),
            'password' => config('mail.mailers.smtp.password'),
            'encryption' => config('mail.mailers.smtp.encryption'),
            'from' => $this->email,
        ];


        //SMTP service
        $smtpService = $this->createEmailService(
            $workspace,
            EmailServiceType::SMTP,
            $smtpSettings
        );

        //Zepto service
        $zepto = $this->createEmailService(
            $workspace,
            EmailServiceType::ZEPTO,
            []
        );

        //Track Service
        $track = $this->createEmailService(
            $workspace,
            EmailServiceType::TRACK,
            []
        );

        //Print Service
        $print = $this->createEmailService(
            $workspace,
            EmailServiceType::PRINT,
            []
        );


        //send grid
        $sendGrid = $this->createEmailService(
            $workspace,
            EmailServiceType::SENDGRID,
            [
                'api_key' => config('services.sendgrid.api_key'),
                'domain' => config('ervices.sendgrid.domain'),
            ]
        );


        $template = $this->setupTemplate($workspace);

        //this will mean here we need to create different campaigns foreach of the types
        $campaign = $this->createCampaign($workspace, $template, $smtpService);
        $campaign2 = $this->createCampaign($workspace, $template, $zepto);
        $campaign3 = $this->createCampaign($workspace, $template, $track);
        $campaign4 = $this->createCampaign($workspace, $template, $print);
        $campaign5 = $this->createCampaign($workspace, $template, $sendGrid);

        $this->createSubscribers($workspace, $campaign);
    }

    private function createUser(): User
    {
        return User::factory()->create($this->defaultUserData);
    }

    private function setupWorkspace(User $user): Workspace
    {
        $workspace = $this->createWorkspace([
            'name' => "Laravel Mail",
            'owner_id' => $user->id,
        ], $user);

        $this->updateUserWorkspace($user, $workspace);

        return $workspace;
    }

    private function updateUserWorkspace(User $user, Workspace $workspace): void
    {
        $user->current_workspace_id = $workspace->id;
        $user->save();
    }

    private function setupTemplate(Workspace $workspace): Template
    {
        $templateContent = file_get_contents($this->templatePath);

        return $this->createTemplate($workspace, "Warm Lead", $templateContent);
    }

    public function createSubscribers(Workspace $workspace, $campaign): void
    {

        $emails = [];

        // Read emails from multiple CSV files in the directory
        $this->loadEmailsFromCsvDirectory($emails);

        // Shuffle and select the required sample
        $selectedEmails = $this->selectRandomEmails($emails);

        $tag = $this->createTags($workspace);

        //Create subscribers and associate them with today's campaign and developers tag
        $this->createSubscriberRecords($selectedEmails, $workspace, $campaign, $tag);

        Log::info("Created " . self::SAMPLE_SIZE . " subscribers and associated them with today's campaign.");
    }

    private function loadEmailsFromCsvDirectory(array &$emails): void
    {
        $dayOfYear = date('z') + 1; // Get current day of the year (1-based)
        $expectedFileName = "laravelmail_{$dayOfYear}.csv";

        $filePath = $this->csvDirectory . $expectedFileName;

        if (!file_exists($filePath)) {
            throw new RuntimeException("File not found: {$filePath}");
        }

        $this->loadEmailsFromCsvFile($filePath, $emails);
    }

    private function loadEmailsFromCsvFile(string $filePath, array &$emails): void
    {

        $handle = fopen($filePath, 'r');
        if ($handle === false) {
            throw new RuntimeException("Failed to open CSV file: {$filePath}");
        }

        Log::info("Processing file: {$filePath}");

        // Skip header row
        fgetcsv($handle);

        // Read file row by row
        while (($data = fgetcsv($handle)) !== false) {
            $emails[] = $this->validateCsvRow($data);
        }

        fclose($handle);
    }

    private function validateCsvRow(array $data): array
    {
        return [
            'email' => $data[self::EMAIL_INDEX] ?? null,
            'first_name' => $data[self::FIRST_NAME_INDEX] ?? null,
            'last_name' => $data[self::LAST_NAME_INDEX] ?? null,
        ];
    }

    private function selectRandomEmails(array $emails): array
    {
        if (count($emails) < self::SAMPLE_SIZE) {
            Log::error(sprintf(
                'Not enough email records in CSV files. Required: %d, Found: %d',
                self::SAMPLE_SIZE,
                count($emails)
            ));
            return $emails;
        }

        shuffle($emails);
        return array_slice($emails, 0, self::SAMPLE_SIZE);
    }

    private function createSubscriberRecords(array $emails, Workspace $workspace, Campaign $campaign, Tag $tag): void
    {
        foreach ($emails as $email) {

            $this->importSubscriberService->import($workspace->id, [
                'email' => $email["email"],
                'first_name' => $email["first_name"],
                'last_name' => $email["last_name"],
                'tags' => [$tag->id],
            ]);
        }
    }

    private function createTags(Workspace $workspace): Tag
    {
        return Tag::create([
            'workspace_id' => $workspace->id,
            'name' => 'Subscribers ' . uniqid(),
        ]);
    }
}
