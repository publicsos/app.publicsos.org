<?php
declare(strict_types=1);
namespace Modules\Mail\Services\Messages;

use Exception;
use Modules\Mail\Models\EmailService;
use Modules\Mail\Models\Message;
use Modules\Mail\Repositories\Campaigns\CampaignTenantRepositoryInterface;
use Modules\Mail\Repositories\AutomationScheduleRepository;
use Modules\Workflow\Repositories\Workflows\WorkflowRepository;

class ResolveEmailService
{
    /** @var CampaignTenantRepositoryInterface */
    private CampaignTenantRepositoryInterface $campaignTenantRepository;

    public function __construct(CampaignTenantRepositoryInterface $campaignTenantRepository)
    {
        $this->campaignTenantRepository = $campaignTenantRepository;
    }

    /**
     * @throws Exception
     */
    public function handle(Message $message): EmailService
    {
        //TODO  - this can be taken out as we dont use  automation is where automation happens
        if ($message->isWorkflow()) {
            return $this->resolveWorkflowEmailService($message);
        }

        if ($message->isCampaign()) {
            return $this->resolveCampaignEmailService($message);
        }

        throw new Exception('Unable to resolve email service for message id=' . $message->id);
    }

    /**
     * Resolve the email service for an automation
     * Reverse engineer the AutomationScheduleRepository
     * @param Message $message
     * @return EmailService
     * @throws Exception
     */
    protected function resolveWorkflowEmailService(Message $message): EmailService
    {

        if (! $automationSchedule = app(WorkflowRepository::class)->find(
            $message->source_id,
            ['automation_step.automation.email_service.type']
        )) {
            throw new Exception('Unable to resolve automation schedule for message id=' . $message->id);
        }

        if (! $emailService = $automationSchedule->automation_step->automation->email_service) {
            throw new Exception('Unable to resolve email service for message id=' . $message->id);
        }

        return $emailService;
    }

    /**
     * Resolve the provider for a campaign
     *
     * @param Message $message
     * @return EmailService
     * @throws Exception
     */
    protected function resolveCampaignEmailService(Message $message): EmailService
    {
        if (! $campaign = $this->campaignTenantRepository->find($message->workspace_id, $message->source_id, ['email_service'])) {
            throw new Exception('Unable to resolve campaign for message id=' . $message->id);
        }

        if (! $emailService = $campaign->email_service) {
            throw new Exception('Unable to resolve email service for message id=' . $message->id);
        }

        return $emailService;
    }
}
